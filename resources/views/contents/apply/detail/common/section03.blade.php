<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.3') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" has-files onsubmit="return false;">
                {{-- 1. --}}

                <x-section-header>
                    <x-slot name="header_title">ア 提供依頼申出者の情報</x-slot>
                </x-section-header>

                <x-form-section>
                    <x-slot name="form">
                        <div class="col-span-6">
                            {{-- alpine.js の変数のスコープがタグで決まっているっぽいので注意 applicantType を利用したい範囲はdiv内に収める --}}
                            <div x-data="{ applicantType: {{$formValues->get('10_applicant_type')??1}} }">

                            {{-- 個人/法人の選択 --}}
                            <x-form-label for="10_applicant_type">{{config('app-ncc01.question-item-name.10_applicant_type')}}</x-form-label>
                            <x-form-input-radio
                                id="10_applicant_type_1"
                                name="10_applicant_type"
                                value="1"
                                x-on:click="applicantType=1"
                                checked-value="{{$formValues->get('10_applicant_type')}}"
                                :disabled="$isLocked"
                            >個人</x-form-input-radio>
                            <x-form-input-radio
                                id="10_applicant_type_2"
                                name="10_applicant_type"
                                value="2"
                                x-on:click="applicantType=2"
                                checked-value="{{$formValues->get('10_applicant_type')}}"
                                :disabled="$isLocked"
                            >法人</x-form-input-radio>

                                {{-- 個人が選択されている場合に表示する領域 --}}
                                <div x-show="applicantType==1">
                                    <x-form-label for="10_applicant_name" class="mt-2">{{config('app-ncc01.question-item-name.10_applicant_name_1')}}</x-form-label>
                                    <x-form-input id="10_applicant_name" name="10_applicant_name_1" type="text" value="{{$formValues->get('10_applicant_name')}}" class="block w-full" :disabled="$isLocked"/>

                                    <x-form-label for="10_applicant_address" class="mt-2">{{config('app-ncc01.question-item-name.10_applicant_address_1')}}</x-form-label>
                                    <x-form-input id="10_applicant_address" name="10_applicant_address_1" type="text" value="{{$formValues->get('10_applicant_address')}}" class="block w-full" :disabled="$isLocked"/>

                                    <x-form-label for="10_applicant_birthday" class="mt-2">{{config('app-ncc01.question-item-name.10_applicant_birthday')}}</x-form-label>
                                    <x-form-error field="10_applicant_birthday"/>
                                    <x-form-input id="10_applicant_birthday" name="10_applicant_birthday_1" type="date" value="{{$formValues->get('10_applicant_birthday')}}" class="block w-full" :disabled="$isLocked"/>

                                    {{-- 所属 --}}
                                    <x-form-label for="affiliation" class="mt-2">{{config('app-ncc01.question-item-name.affiliation_1')}}</x-form-label>
                                    <x-form-input id="affiliation" name="affiliation_1" type="text" value="{{$formValues->get('affiliation')}}" class="block w-full" :disabled="$isLocked"/>
                                </div>

                                {{-- 法人が選択されている場合に表示する領域 --}}
                                <div x-show="applicantType==2">
                                    <x-form-label for="affiliation_2" class="mt-2">{{config('app-ncc01.question-item-name.affiliation_2')}}</x-form-label>
                                    <x-form-input id="affiliation_2" name="affiliation_2" type="text" value="{{$formValues->get('affiliation')}}" class="block w-full" :disabled="$isLocked"/>

                                    <x-form-label for="10_applicant_name_2" class="mt-2">{{config('app-ncc01.question-item-name.10_applicant_name_2')}}</x-form-label>
                                    <x-form-input id="10_applicant_name_2" name="10_applicant_name_2" type="text" value="{{$formValues->get('10_applicant_name')}}" class="block w-full" :disabled="$isLocked"/>

                                    <x-form-label for="10_applicant_address_2" class="mt-2">{{config('app-ncc01.question-item-name.10_applicant_address_2')}}</x-form-label>
                                    <x-form-input id="10_applicant_address_2" name="10_applicant_address_2" type="text" value="{{$formValues->get('10_applicant_address')}}" class="block w-full" :disabled="$isLocked"/>
                                </div>

                            </div>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-section-border-header />

                <x-section-header>
                    <x-slot name="header_title">イ 利用者の範囲（氏名、所属、職名）</x-slot>
                </x-section-header>

                {{-- ここは添添付文書 --}}
                @yield('section3-attachments')

                <x-section-border/>

                {{-- 利用者 --}}
                <livewire:apply-users :formValues="$formValues" :isLocked="$isLocked"/>

                <x-section-border/>
                {{-- ボタン --}}
                <x-action-area>
                    <a href="{{route('apply.detail.overview',['applyId'=>$id])}}"><x-button-secondary class="mr-2" type="button">戻る</x-button-secondary></a>
                    {{-- 保存ボタン --}}
                    @if($canModifyApply)
                        <livewire:save-apply-temporarily :is-locked="$isLocked" />
                    @endif
                </x-action-area>

            </x-buk-form>

        </div>
    </div>

</x-app-layout>
