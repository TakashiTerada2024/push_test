<span x-data>
    <x-button-primary
        type="button"
        x-on:click="
            const form = $el.closest('form');
            if (!form) return;

            if (form.checkValidity()) {
                $wire.confirmSendToSecretariat()
            } else {
                form.reportValidity();
                $wire.$set('confirming', false);
            }"
        :disabled="$isLocked"
        class="{{ $isLocked ? 'opacity-50 cursor-not-allowed' : '' }}"
    >一時保存</x-button-primary>

    {{-- モーダル --}}
    <div class="text-left">
        <x-jet-dialog-modal wire:model="confirming">
            <x-slot name="title">確認</x-slot>
            <x-slot name="content">
                一時保存時のオプションを選択してください。<br />
                <x-form-input-checkbox-with-label name="notify_flag">一時保存を事務局に通知する</x-form-input-checkbox-with-label>

            </x-slot>
            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirming')" wire:loading.attr="disabled">
                    一時保存をキャンセル
                </x-jet-secondary-button>

                <x-button-primary type="button" onclick="submit();" wire:loading.attr="disabled">
                    一時保存の実行
                </x-button-primary>
            </x-slot>
        </x-jet-dialog-modal>
    </div>

</span>



