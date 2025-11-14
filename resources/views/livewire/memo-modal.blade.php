<tr>
<td class="text-center">
        <button wire:click="openModal()" type="button" class="{{ $statusClass }}">
        <svg width="35px" height="50px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve">
            <g>
                <polygon class="st0" points="16.1,14.2 16.1,8.1 11.6,3.5 4.5,3.5 4.5,19.2 11.2,19.2  "/>
                <polygon class="st0" points="14.9,19.2 16.1,19.2 16.1,18  "/>
                <polyline class="st0" points="11.6,3.5 11.6,8.1 16.1,8.1  "/>
                <polygon class="st0" points="12.7,21.4 10.2,22 10.8,19.5 19.6,10.8 21.5,12.6  "/>
                <line class="st0" x1="17.1" y1="13.3" x2="19" y2="15.1"/>
            </g>
        </svg>
        </button>

        @if($showModal)
            <div class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>               
    
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full center-div">
                    @if (session()->has('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif
                    @error('memo')
                        <div class="alert alert-error">{{ $message }}</div>
                    @enderror
                        <form wire:submit.prevent="save">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    メモ
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        申出ID {{ $applyId }}
                                    </p>
                                    <textarea style="margin: padding 5%; width: 100%;" id="memo" name="memo" class="memo" rows="6" wire:model="memo"></textarea>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-gray-100 text-right">
                                <x-jet-secondary-button wire:click="closeModal()" wire:loading.attr="disabled">
                                    閉じる
                                </x-jet-secondary-button>

                                <x-button-primary type="submit">
                                    保存
                                </x-button-primary>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
</td>
<td class="py-2">
    <p class="pt-2" >{!! nl2br(e($firstFewCharacters)) !!}</p>
</td>
</tr>