<div>
    <!-- 申出スキップURL発行ボタン -->
    <div>
        <button wire:click="openModal" type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md h-[42px] text-sm font-medium hover:bg-gray-700 w-48">
            申出スキップURL発行
        </button>
    </div>

    <!-- スキップURL発行モーダルウィンドウ -->
    @if($isOpen)
    <div class="absolute right-0 top-[46px] z-50 w-[400px]" style="min-width: 12rem;">
        <!-- モーダルコンテンツ -->
        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl border border-gray-200">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div>
                    <div class="text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            申出スキップURL発行
                        </h3>
                        <div class="mt-4">
                            @if(empty($skipUrlText))
                                <!-- 申出種別選択フォーム -->
                                <form wire:submit.prevent="generateUrl" class="space-y-4">
                                    <div>
                                        <label class="block font-medium text-gray-700 mb-2">申出種別</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach($applyTypes as $id => $name)
                                            <div class="flex items-center">
                                                <input type="radio" id="type-{{ $id }}" name="applyTypeId" wire:model="applyTypeId" value="{{ $id }}" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <label for="type-{{ $id }}" class="ml-2 block text-sm text-gray-700">{{ $name }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @error('applyTypeId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            URL発行
                                        </button>
                                    </div>
                                </form>
                            @else
                                <!-- URL表示エリア -->
                                <div>
                                    <label class="block font-medium text-gray-700 mb-2">スキップURL</label>
                                    <div class="relative">
                                        <textarea id="skipUrlText" readonly class="form-textarea mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-32">{{ $skipUrlText }}</textarea>
                                    </div>
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button type="button" id="copyButton" onclick="copyToClipboard()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                            コピー
                                        </button>
                                        <button type="button" wire:click="closeModal" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            閉じる
                                        </button>
                                    </div>
                                </div>
                                <!-- クリップボードにコピーするスクリプト -->
                                <script>
                                    function copyToClipboard() {
                                        const textarea = document.getElementById('skipUrlText');
                                        textarea.select();
                                        document.execCommand('copy');
                                        
                                        // コピー成功メッセージ
                                        const copyButton = document.getElementById('copyButton');
                                        const originalText = copyButton.innerHTML;
                                        copyButton.innerHTML = '<svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>コピー完了';
                                        copyButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                                        copyButton.classList.add('bg-green-500', 'hover:bg-green-600');
                                        
                                        setTimeout(() => {
                                            copyButton.innerHTML = originalText;
                                            copyButton.classList.add('bg-green-600', 'hover:bg-green-700');
                                            copyButton.classList.remove('bg-green-500', 'hover:bg-green-600');
                                        }, 2000);
                                    }
                                </script>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(empty($skipUrlText))
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse">
                <button type="button" wire:click="closeModal" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    キャンセル
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif
</div> 