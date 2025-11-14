<div>
    <button type=button wire:click="openModal">
        <span class="text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span> 承認
    </button>

    <x-buk-form method="POST" action="{{route('apply.start_submitting_document',['applyId'=>$applyId])}}">
        {{-- モーダル --}}
        <div class="text-left">
            <x-jet-dialog-modal wire:model="confirming">
                <x-slot name="title">承認依頼の許可</x-slot>
                <x-slot name="content">
                    <div class="pb-3">申出:{{$applyId}} 「{{$applySubject}}」 の記入内容を承認し、ステータスを「申出文書 提出中」に変更します。<br>
                        <ul style="margin-top: 1rem;">
                            <li type="disc" style="border-bottom: none;">メモの内容を確認しましたか？</li>
                        </ul>
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirming')" wire:loading.attr="disabled">
                        キャンセル
                    </x-jet-secondary-button>

                    <x-button-primary type="submit" wire:loading.attr="disabled">
                        実行
                    </x-button-primary>
                </x-slot>
            </x-jet-dialog-modal>
        </div>
    </x-buk-form>
</div>
