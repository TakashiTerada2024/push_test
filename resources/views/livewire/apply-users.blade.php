<div>
    {{-- 利用人数の入力フォーム --}}
    <x-form-section>
        <x-slot name="title">利用人数</x-slot>

        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-form-error field="3_number_of_users"/>
                <x-form-input
                    wire:model.lazy="numberOfUsers"
                    id="3_number_of_users"
                    name="3_number_of_users"
                    type="number"
                    min="1"
                    class="block w-full"
                    :disabled="$isLocked"/>
            </div>
        </x-slot>
    </x-form-section>

    <x-section-border/>

    {{-- 以下、利用者の詳細情報入力フォーム --}}
    @for ($i = 0; $i < $numberOfUsers; $i++)
        <x-form-section>
            <x-slot name="title">利用者{{($i+1)}}.</x-slot>
            <x-slot name="description">
                ※すべての利用者分、記入すること。
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6 sm:col-span-6">
                    <x-form-label class="mt-0" for="apply_users[name][{{$i}}]">氏名</x-form-label>
                    <x-form-error field="apply_users.{{$i}}.name"/>
                    <x-form-input wire:model="applyUsers.{{$i}}.name" id="apply_users[{{$i}}][name]" name="apply_users[{{$i}}][name]" type="text" class="block w-full" :disabled="$isLocked"/>

                    <x-form-label class="mt-2" for="apply_users[1][institution]">所属</x-form-label>
                    <x-form-error field="apply_users.{{$i}}.institution"/>
                    <x-form-input-textarea wire:model="applyUsers.{{$i}}.institution" id="apply_users[{{$i}}][institution]" name="apply_users[{{$i}}][institution]" type="text" rows="3" class="block w-full" :disabled="$isLocked"/>
                    <x-form-helper-text>
                        所属が複数ある場合は、全ての所属を記載すること。
                    </x-form-helper-text>

                    <x-form-label class="mt-2" for="apply_users[{{$i}}][position]">職名</x-form-label>
                    <x-form-error field="apply_users.{{$i}}.position"/>
                    <x-form-input-textarea wire:model="applyUsers.{{$i}}.position" id="apply_users[{{$i}}][position]" name="apply_users[{{$i}}][position]" type="text" rows="3" class="block w-full" :disabled="$isLocked"/>
                    <x-form-helper-text>
                        所属が複数ある場合は、全ての所属及び所属における職名又は立場を記載すること。
                    </x-form-helper-text>

                    <x-form-label class="mt-2" for="apply_users[{{$i}}][role]">役割</x-form-label>
                    <x-form-error field="apply_users.{{$i}}.role"/>
                    <x-form-input-textarea wire:model="applyUsers.{{$i}}.role" id="apply_users[{{$i}}][role]" name="apply_users[{{$i}}][role]" type="text" rows="3" class="block w-full" :disabled="$isLocked"/>
                    <x-form-helper-text>
                        「提供依頼申出者」、調査研究全体の安全管理の責任を担う「統括利用責任者」、利用場所が複数ある場合は各利用場所において情報の安全管理の責任を担う「利用責任者」を必ず記載すること（どの利用場所かを明確にすること）。
                    </x-form-helper-text>
                </div>
            </x-slot>
        </x-form-section>
        <x-section-border/>

    @endfor
</div>
