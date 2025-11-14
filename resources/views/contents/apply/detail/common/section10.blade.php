<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.10') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" onsubmit="return false;">
                <x-form-section>
                    <x-slot name="title">事務担当者連絡先</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-label for="10_clerk_name">{{config('app-ncc01.question-item-name.10_clerk_name')}}</x-form-label>
                            <x-form-input id="10_clerk_name" name="10_clerk_name" type="text" value="{{$formValues->get('10_clerk_name')}}" class="block w-full" :disabled="$isLocked"/>

                            <x-form-label for="10_clerk_contact_address" class="mt-2">{{config('app-ncc01.question-item-name.10_clerk_contact_address')}}</x-form-label>
                            <x-form-input id="10_clerk_contact_address" name="10_clerk_contact_address" type="text" value="{{$formValues->get('10_clerk_contact_address')}}" class="block w-full" :disabled="$isLocked"/>

                            <x-form-label for="10_clerk_contact_email" class="mt-2">{{config('app-ncc01.question-item-name.10_clerk_contact_email')}}</x-form-label>
                            <x-form-input id="10_clerk_contact_email" name="10_clerk_contact_email" type="text" value="{{$formValues->get('10_clerk_contact_email')}}" class="block w-full" :disabled="$isLocked"/>

                            <x-form-label for="10_clerk_contact_phone_number" class="mt-2">{{config('app-ncc01.question-item-name.10_clerk_contact_phone_number')}}</x-form-label>
                            <x-form-input id="10_clerk_contact_phone_number" name="10_clerk_contact_phone_number" type="text" value="{{$formValues->get('10_clerk_contact_phone_number')}}" class="block w-full" :disabled="$isLocked"/>

                            <x-form-label for="10_clerk_contact_extension_phone_number" class="mt-2">{{config('app-ncc01.question-item-name.10_clerk_contact_extension_phone_number')}}</x-form-label>
                            <x-form-input id="10_clerk_contact_extension_phone_number" name="10_clerk_contact_extension_phone_number" type="text" value="{{$formValues->get('10_clerk_contact_extension_phone_number')}}" class="block w-full" :disabled="$isLocked"/>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-section-border />

                <x-form-section>
                    <x-slot name="title">{{config('app-ncc01.question-item-name.10_remark')}}</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-input-textarea
                                id="10_remark"
                                name="10_remark"
                                class="block w-full"
                                rows="6"
                                :disabled="$isLocked"
                            >{{$formValues->get('10_remark')}}</x-form-input-textarea>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-section-border/>

                {{-- ボタン --}}
                <x-action-area>
                    <a href="{{route('apply.detail.overview',['applyId'=>$id])}}">
                        <x-button-secondary class="mr-2" type="button">戻る</x-button-secondary>
                    </a>
                    {{-- 保存ボタン --}}
                    @if($canModifyApply)
                    <livewire:save-apply-temporarily :is-locked="$isLocked" />
                    @endif
                </x-action-area>
            </x-buk-form>
        </div>
    </div>
</x-app-layout>
