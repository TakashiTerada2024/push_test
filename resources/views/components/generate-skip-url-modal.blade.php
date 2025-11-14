@props(['applyTypes' => []])

<!-- ページの先頭に追加するCSRFトークン設定 -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('skipUrlModal', () => ({
            isOpen: false,
            applyTypeId: null,
            skipUrlText: '',
            isGenerating: false,
            showCopySuccess: false,
            expiredAt: null,
            
            toggleModal() {
                this.isOpen = !this.isOpen;
                if (!this.isOpen) {
                    this.resetForm();
                }
            },
            
            resetForm() {
                this.applyTypeId = null;
                this.skipUrlText = '';
                this.isGenerating = false;
                this.expiredAt = null;
            },
            
            formatExpiredAt(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.getFullYear() + '年' + 
                       (date.getMonth() + 1) + '月' + 
                       date.getDate() + '日 ' + 
                       date.getHours().toString().padStart(2, '0') + ':' + 
                       date.getMinutes().toString().padStart(2, '0');
            },
            
            async generateUrl() {
                if (!this.applyTypeId) {
                    alert('申出種別を選択してください');
                    return;
                }
                
                this.isGenerating = true;
                
                try {
                    const response = await fetch('/api/generate-skip-url', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            apply_type_id: this.applyTypeId
                        })
                    });
                    
                    if (!response.ok) {
                        throw new Error(`APIエラー: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // URLの形式：http(s)://mydomain/apply/{ULID}
                        const baseUrl = window.location.protocol + '//' + window.location.host;
                        const url = `${baseUrl}/apply/${data.ulid}`;
                        // 有効期限をフォーマット
                        const expDate = new Date(data.expired_at);
                        const formattedDate = expDate.getFullYear() + '-' + 
                                            String(expDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                            String(expDate.getDate()).padStart(2, '0') + ' ' + 
                                            String(expDate.getHours()).padStart(2, '0') + ':' + 
                                            String(expDate.getMinutes()).padStart(2, '0');
                        
                        // URLと説明文を組み合わせる
                        this.skipUrlText = `${url}\n\nこのURLの有効期限は${formattedDate}です。期限までに申出の作成を実施してください。`;
                        this.expiredAt = data.expired_at;
                    } else {
                        alert('URLの生成に失敗しました: ' + (data.message || '不明なエラー'));
                    }
                } catch (error) {
                    console.error('Error generating URL:', error);
                    alert('URLの生成中にエラーが発生しました: ' + error.message);
                } finally {
                    this.isGenerating = false;
                }
            },
            
            copyToClipboard() {
                const textarea = this.$refs.skipUrlText;
                textarea.select();
                document.execCommand('copy');
                
                this.showCopySuccess = true;
                setTimeout(() => {
                    this.showCopySuccess = false;
                }, 2000);
            }
        }));
    });
</script>

<div x-data="skipUrlModal" class="relative">

    <!-- 申出スキップURL発行ボタン -->
    <div>
        <button @click="toggleModal" type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md h-[42px] text-sm font-medium hover:bg-gray-700 w-48">
            申出スキップURL発行
        </button>
    </div>

    <!-- スキップURL発行モーダルウィンドウ -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 top-[46px] z-50 w-[800px]" 
         style="min-width: 800px; display: none;">
         
        <!-- モーダルコンテンツ -->
        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl border border-gray-200">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div>
                    <div class="text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            申出スキップURL発行
                        </h3>
                        <div class="mt-4">
                            <template x-if="!skipUrlText">
                                <!-- 申出種別選択フォーム -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block font-medium text-gray-700 mb-4">申出種別</label>
                                        <div class="px-4 mb-4">
                                            <div class="flex flex-row justify-between">
                                                @foreach($applyTypes as $id => $name)
                                                <div class="flex items-center">
                                                    <input type="radio" id="type-{{ $id }}" name="applyTypeId" x-model="applyTypeId" value="{{ $id }}" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                    <label for="type-{{ $id }}" class="ml-2 text-sm text-gray-700">{{ $name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button @click="generateUrl" 
                                                :disabled="isGenerating || !applyTypeId"
                                                type="button" 
                                                class="bg-gray-800 text-white px-4 py-2 rounded-md h-[42px] text-sm font-medium hover:bg-gray-700 disabled:opacity-50 disabled:hover:bg-gray-800">
                                            <span x-show="!isGenerating">URL発行</span>
                                            <span x-show="isGenerating">処理中...</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="skipUrlText">
                                <!-- URL表示エリア -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">スキップURL</label>
                                    <div class="relative">
                                        <textarea x-ref="skipUrlText" readonly x-text="skipUrlText" class="form-textarea mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" style="height: 12rem;"></textarea>
                                        <div x-show="expiredAt" class="mt-2 text-sm text-gray-600">
                                            <span class="font-semibold">有効期限:</span> <span x-text="formatExpiredAt(expiredAt)"></span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button @click="copyToClipboard" 
                                                type="button" 
                                                class="bg-gray-800 text-white px-4 py-2 rounded-md h-[42px] text-sm font-medium hover:bg-gray-700">
                                            <span x-show="!showCopySuccess" class="flex items-center">
                                                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                                コピー
                                            </span>
                                            <span x-show="showCopySuccess" class="flex items-center">
                                                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                コピー完了
                                            </span>
                                        </button>
                                        <button @click="toggleModal" 
                                                type="button" 
                                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md h-[42px] text-sm font-medium hover:bg-gray-400">
                                            閉じる
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <template x-if="!skipUrlText">
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse">
                    <button @click="toggleModal" 
                            type="button" 
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md h-[42px] text-sm font-medium hover:bg-gray-400">
                        キャンセル
                    </button>
                </div>
            </template>
        </div>
    </div>
</div> 