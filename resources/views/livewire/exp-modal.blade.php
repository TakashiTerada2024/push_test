<div>
    <button type="submit" wire:click.prevent="showModal()" class="'inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">{{ $title }}</button>

    <x-jet-modal wire:model="expModal">
        <x-slot name="slot">
            <div class=" py-20 exp-modal">
                <div class="text-lg text-center font-bold">{{ $title }}</div>
                <div class="justify-between items-center px-6 py-20">
                    <div class="exp">{!! $exp !!}</div>
                </div>
                <div class="text-center">
                    <button type="button" wire:click.prevent="closeModal()" class="'inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">閉じる</button>
                </div>
            </div>
        </x-slot>
    </x-jet-modal>
</div>
