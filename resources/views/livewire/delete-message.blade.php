<div>
    <form method="POST" wire:submit.prevent="submit">
        {{-- モーダル --}}
        <div class="text-left">
            <x-jet-dialog-modal wire:model="deleteMessage">

                <x-slot name="title">メッセージ削除</x-slot>
                <x-slot name="content">
                    <div class="pb-3">メッセージを削除します。実行してよろしいですか？</div>
                    @error('notificationId')<span class="text-red-500">{{ $message }}</span> @enderror
                    <textarea
                        id="message_body"
                        name="message_body"
                        class="hidden"
                        rows="6"
                        placeholder=""
                        wire:model="messageBody"
                    ></textarea>
                </x-slot>
                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('deleteMessage')" wire:loading.attr="disabled">
                        キャンセル
                    </x-jet-secondary-button>

                    <x-button-primary type="submit" wire:loading.attr="disabled">
                        実行
                    </x-button-primary>
                </x-slot>
            </x-jet-dialog-modal>
        </div>
    </form>
</div>
