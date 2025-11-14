@inject('prefectures','Ncc01\Common\Enterprise\Classification\Prefectures')
@inject('isRequired','Ncc01\Common\Enterprise\Classification\IsRequired')
@inject('yearsOfDiagnose','Ncc01\Apply\Enterprise\Classification\YearsOfDiagnose')
@inject('icdTypes','Ncc01\Apply\Enterprise\Classification\IcdTypes')
@inject('sexes','Ncc01\Apply\Enterprise\Classification\Sexes')
@inject('rangesOfAgeType','Ncc01\Apply\Enterprise\Classification\RangeOfAgeTypes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.4') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" has-files onsubmit="return false;">

                {{-- 1. --}}
                <x-form-section>
                    <x-slot name="title">ア {{config('app-ncc01.question-item-name.4_year_of_diagnose')}}</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-label for="">{{config('app-ncc01.question-item-name.4_year_of_diagnose')}}</x-form-label>
                            <x-form-error field="4_year_of_diagnose_start" />
                            <x-form-error field="4_year_of_diagnose_end" />
                            <x-form-input-select
                                id="4_year_of_diagnose_start"
                                name="4_year_of_diagnose_start"
                                :value="$formValues->get('4_year_of_diagnose_start')"
                                :options="$yearsOfDiagnose"
                                :disabled="$isLocked"
                            />～<x-form-input-select
                                id="4_year_of_diagnose_end"
                                name="4_year_of_diagnose_end"
                                :value="$formValues->get('4_year_of_diagnose_end')"
                                :options="$yearsOfDiagnose"
                                :disabled="$isLocked"
                            />
                            <x-form-helper-text>現時点で提供可能な診断年次を選択すること。</x-form-helper-text>
                        </div>
                    </x-slot>
                </x-form-section>
                <x-section-border />

                {{-- 2. --}}
                <x-form-section>
                    <x-slot name="title">イ {{config('app-ncc01.question-item-name.4_area_type')}}</x-slot>
                    <x-slot name="form">

                        <div class="col-span-6" x-data="{ prefectures: [{{implode(',',$formValues->get('4_area_prefectures')??[])}}] }">
                            <x-form-error field="4_area_prefectures" />
                            <div>
                                <x-button-secondary x-on:click="prefectures=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47]" :disabled="$isLocked">全て選択</x-button-secondary>
                                <x-button-secondary x-on:click="prefectures=[]" :disabled="$isLocked">選択解除</x-button-secondary>
                            </div>

                            <hr style="margin-top:8px;margin-bottom: 8px;" />
                            <div>

                            @for ($i = 1; $i <= 7; $i++)
                                <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                            @endfor
                            </div>

                            <div class="">
                                @for ($i = 8; $i <= 14; $i++)
                                    <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                                @endfor
                            </div>

                            <div class="">
                                @for ($i = 15; $i <= 20; $i++)
                                    <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                                @endfor
                            </div>

                            <div class="">
                                @for ($i = 21; $i <= 24; $i++)
                                    <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                                @endfor
                            </div>

                            <div class="">
                                @for ($i = 25; $i <= 30; $i++)
                                    <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                                @endfor
                            </div>

                            <div class="">
                                @for ($i = 31; $i <= 35; $i++)
                                    <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                                @endfor
                            </div>

                            <div class="">
                                @for ($i = 36; $i <= 39; $i++)
                                    <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                                @endfor
                            </div>

                            <div class="">
                                @for ($i = 40; $i <= 47; $i++)
                                    <x-form-input-checkbox-with-label x-model="prefectures" id="4_area_prefectures_{{$i}}" name="4_area_prefectures[]" value="{{$i}}" :checkedValue="$formValues->get('4_area_prefectures')" :disabled="$isLocked">{{$prefectures->value($i)}}</x-form-input-checkbox-with-label>
                                @endfor
                            </div>
                        </div>
                    </x-slot>
                </x-form-section>
                <x-section-border />

                {{-- 3. --}}
                <x-form-section>
                    <x-slot name="title">ウ がんの種類</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-label class="mt-0" for="">{{config('app-ncc01.question-item-name.4_idc_type')}}</x-form-label>
                            <x-form-error field="4_idc_type"/>
                            <x-form-input-radios
                                id="4_idc_type"
                                name="4_idc_type"
                                :options="$icdTypes"
                                :checked-value="$formValues->get('4_idc_type')"
                                :disabled="$isLocked"
                            />
                        </div>

                        <div class="col-span-6">
                            <x-form-label class="mt-0" for="">{{config('app-ncc01.question-item-name.4_idc_detail')}}</x-form-label>
                            <x-form-error field="4_idc_detail"/>
                            <x-form-input-textarea
                                id="4_idc_detail"
                                name="4_idc_detail"
                                class="block w-full"
                                rows="2"
                                placeholder="例:膀胱癌(C67 すべて)"
                                :disabled="$isLocked"
                            >{{$formValues->get('4_idc_detail')}}</x-form-input-textarea>
                            <x-form-helper-text>
                                ICD-10（国際疾病分類第10版）又は、ICD-O-3（国際疾病分類腫瘍学第3版）の分類で、提供を希望するがんの種類を特定して記載すること。<br>
                                例：膀胱癌（ICD-10のC67すべて）
                            </x-form-helper-text>
                        </div>
                    </x-slot>
                </x-form-section>
                <x-section-border />

                {{-- 4. --}}
                <x-form-section>
                    <x-slot name="title">エ 生存確認情報</x-slot>
                    <x-slot name="description">※該当するものを選択</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <div>
                                <x-form-label for="">{{config('app-ncc01.question-item-name.4_is_alive_required')}}</x-form-label>
                                <x-form-error field="4_is_alive_required"/>
                                <x-form-input-radios
                                    id="4_is_alive_required"
                                    name="4_is_alive_required"
                                    :options="$isRequired"
                                    :checked-value="$formValues->get('4_is_alive_required')"
                                    :disabled="$isLocked"
                                />
                            </div>

                            <div class="mt-2">
                                <x-form-label for="">{{config('app-ncc01.question-item-name.4_is_alive_date_required')}}</x-form-label>
                                <x-form-error field="4_is_alive_date_required"/>
                                <x-form-input-radios
                                    id="4_is_alive_date_required"
                                    name="4_is_alive_date_required"
                                    :options="$isRequired"
                                    :checked-value="$formValues->get('4_is_alive_date_required')"
                                    :disabled="$isLocked"
                                />

                            </div>

                            <div class="mt-2">
                                <x-form-label for="">{{config('app-ncc01.question-item-name.4_is_cause_of_death_required')}}</x-form-label>
                                <x-form-error field="4_is_cause_of_death_required"/>
                                <x-form-input-radios
                                    id="4_is_cause_of_death_required"
                                    name="4_is_cause_of_death_required"
                                    :options="$isRequired"
                                    :checked-value="$formValues->get('4_is_cause_of_death_required')"
                                    :disabled="$isLocked"
                                />
                            </div>

                        </div>
                    </x-slot>
                </x-form-section>
                <x-section-border />

                {{-- 5. --}}
                <x-form-section>
                    <x-slot name="title">オ 属性的範囲</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-label class="mt-0" for="">{{config('app-ncc01.question-item-name.4_sex')}}</x-form-label>
                            <x-form-error field="4_sex"/>
                            <x-form-input-radios
                                id="4_sex"
                                name="4_sex"
                                :options="$sexes"
                                :checked-value="$formValues->get('4_sex')"
                                :disabled="$isLocked"
                            />
                            <x-form-label class="mt-2" for="">{{config('app-ncc01.question-item-name.4_sex')}}(備考)</x-form-label>
                            <x-form-input-textarea
                                id="4_sex_detail"
                                name="4_sex_detail"
                                class="block w-full"
                                rows="4"
                                placeholder=""
                                :disabled="$isLocked"
                            >{{$formValues->get('4_sex_detail')}}</x-form-input-textarea>


                        </div>

                        <div class="col-span-6">
                            {{-- 年齢の選択 --}}
                            @section('age_range_detail')
                            @show

                            <x-form-label class="mt-2" for="">{{config('app-ncc01.question-item-name.4_range_of_age_detail')}}</x-form-label>
                            <x-form-error field="4_range_of_age_detail"/>
                            <x-form-input-textarea
                                id="4_range_of_age_detail"
                                name="4_range_of_age_detail"
                                class="block w-full"
                                rows="4"
                                placeholder="それ以外を選択した場合、要望する提供の形態を記載する。"
                                :disabled="$isLocked"
                            >{{$formValues->get('4_range_of_age_detail')}}</x-form-input-textarea>
                            <x-form-helper-text>
                                当該研究の対象となる年齢の範囲（16歳未満、20歳未満、40歳未満、100歳未満、全年齢等）を記載すること。<br>
                                「それ以外」を選択した場合は、要望する提供の形態を記載すること。
                            </x-form-helper-text>
                        </div>
                    </x-slot>
                </x-form-section>
                <x-section-border />

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
