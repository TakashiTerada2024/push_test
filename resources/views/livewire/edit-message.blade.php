<div>
    <form method="POST" wire:submit.prevent="submit">
        <x-jet-dialog-modal wire:model="editMessage">
        <x-slot name="title">メッセージ編集</x-slot>
        <x-slot name="content">       
            <div class="justify-between items-center px-6 py-20">
                @error('notificationId')<span class="text-red-500">{{ $message }}</span> @enderror
                <textarea
                    id="message_body"
                    name="message_body"
                    class="block w-full"
                    rows="6"
                    placeholder=""
                    wire:model="messageBody"
                ></textarea>
                @error('messageBody')<span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            </x-slot>
            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('editMessage')" >
                    キャンセル
                </x-jet-secondary-button>
                <x-jet-button type="submit" 
                    class="'inline-flex items-center px-4 py-2 bg-accent-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-accent-400 active:bg-accent-700 focus:outline-none focus:border-accent-600 focus:ring focus:ring-accent-300 disabled:opacity-25 transition">
                    更新
                </x-jet-button>
            </x-slot>
        </x-jet-modal>
    </form>
</div>