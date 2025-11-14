@inject('applyType','Ncc01\Apply\Enterprise\Classification\ApplyTypes')

<div>
    <button type=button wire:click="openApplyTypeForm">
        <span class="text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round"
                                                                                         stroke-linejoin="round"
                                                                                         stroke-width="2"
                                                                                         d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></span>
        申出種別変更
    </button>

    <x-buk-form method="POST" action="{{route('apply.change_type',['applyId'=>$applyId])}}">
        {{-- モーダル --}}
        <div class="text-left">
            <x-jet-dialog-modal wire:model="confirming">
                <x-slot name="title">申出種別変更</x-slot>
                <x-slot name="content">
                    <div class="pb-3">申出:{{$applyId}} 「{{$applySubject}}」 の申出種別を選択してください。</div>

                    {{-- ココにフォーム --}}
                    <x-form-input-radios id="apply_type_{{$applyId}}" name="type_id" :options="$applyType->listOfName()"
                                         :checked-value="$applyTypeId"><br/></x-form-input-radios>

                </x-slot>
                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirming')" wire:loading.attr="disabled">
                        キャンセル
                    </x-jet-secondary-button>

                    <x-button-primary type="submit" wire:loading.attr="disabled">
                        申出種別変更
                    </x-button-primary>
                </x-slot>
            </x-jet-dialog-modal>
        </div>
    </x-buk-form>
</div>
