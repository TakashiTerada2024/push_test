<div>
    {{-- The Master doesn't talk, he acts. --}}
    <button type=button wire:click="openModal">
        <span class="text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg></span> 申出中止
    </button>

    <x-buk-form method="POST" action="{{route('apply.cancel',['applyId'=>$applyId])}}">
        {{-- モーダル --}}
        <div class="text-left">
            <x-jet-dialog-modal wire:model="confirming">
                <x-slot name="title">申出中止</x-slot>
                <x-slot name="content">
                    <div class="pb-3">申出:{{$applyId}} 「{{$applySubject}}」 のステータスを「申出中止」に変更します。<br />
                        申出:{{$applyId}} は一覧に表示されなくなります。実行してよろしいですか？</div>
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
