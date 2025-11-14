<div>
    <button type=button wire:click="openModal">
        <span class="text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" x="0px" y="0px" viewBox="0 0 50 50"> <path d="M 25 2 C 12.317 2 2 12.317 2 25 C 2 37.683 12.317 48 25 48 C 37.683 48 48 37.683 48 25 C 48 20.44 46.660281 16.189328 44.363281 12.611328 L 42.994141 14.228516 C 44.889141 17.382516 46 21.06 46 25 C 46 36.579 36.579 46 25 46 C 13.421 46 4 36.579 4 25 C 4 13.421 13.421 4 25 4 C 30.443 4 35.393906 6.0997656 39.128906 9.5097656 L 40.4375 7.9648438 C 36.3525 4.2598437 30.935 2 25 2 z M 43.236328 7.7539062 L 23.914062 30.554688 L 15.78125 22.96875 L 14.417969 24.431641 L 24.083984 33.447266 L 44.763672 9.046875 L 43.236328 7.7539062 z"></path></svg>
        </span> 応諾
    </button>

    <x-buk-form method="POST" action="{{ route('apply.accept', ['applyId' => $applyId]) }}">
        {{-- モーダル --}}
        <div class="text-left">
            <x-jet-dialog-modal wire:model="confirming">
                <x-slot name="title">応諾</x-slot>
                <x-slot name="content">
                    <div class="pb-3">申出:{{$applyId}} 「{{$applySubject}}」 のステータスを「応諾」に変更します。<br />
                        応諾ステータスへの変更を行う前に、必ず申出:{{$applyId}}の <a class="text-blue-500" target="_blank" href={{ route('attachment.apply.show', ['applyId' => $applyId]) }}>添付ファイル画面</a>で、<span class="text-red-500">必要な資料の承認ボタンが全て</span>押されていることを確認してください。実行してよろしいですか？</div>
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

