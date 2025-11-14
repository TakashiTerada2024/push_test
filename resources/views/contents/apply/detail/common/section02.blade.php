<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.2') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" has-files onsubmit="return false;">
                {{-- 1. --}}
                <x-form-section>
                    <x-slot name="title">ア 利用目的及び必要性</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-helper-text>記載例(『全国がん登録 情報の提供マニュアル』 第 8-2(2)表及び第 8-2(3)を参照のこと。)
                                <a href="https://ganjoho.jp/med_pro/cancer_control/can_reg/national/datause/general.html#anchor4">https://ganjoho.jp/med_pro/cancer_control/can_reg/national/datause/general.html#anchor4</a>
                            </x-form-helper-text>

                            <div class="mt-4">
                                <x-form-label for="2_purpose_of_use">{{config('app-ncc01.question-item-name.2_purpose_of_use')}}</x-form-label>
                                <x-form-error field="2_purpose_of_use"/>
                                <x-form-input-textarea
                                    id="2_purpose_of_use"
                                    name="2_purpose_of_use"
                                    class="block w-full"
                                    rows="6"
                                    :disabled="$isLocked"
                                >{{$formValues->get('2_purpose_of_use')}}</x-form-input-textarea>
                                <x-form-helper-text>
                                    調査研究の目的や意義をご記入ください。<br>
                                    例）都道府県別の〇〇を集計し、□□を検討する。本研究により□□の実態が明らかになることで、がん患者を対象とした△△の対策につながる科学的根拠が得られ、わが国のがん対策へ資することが期待される。
                                </x-form-helper-text>
                            </div>

                            <div class="mt-4">
                                <x-form-label for="2_need_to_use">{{config('app-ncc01.question-item-name.2_need_to_use')}}</x-form-label>
                                <x-form-error field="2_need_to_use"/>
                                <x-form-input-textarea
                                    id="2_need_to_use"
                                    name="2_need_to_use"
                                    class="block w-full"
                                    rows="6"
                                    :disabled="$isLocked"
                                >{{$formValues->get('2_need_to_use')}}</x-form-input-textarea>
                                <x-form-helper-text>
                                    調査研究において、全国がん登録情報の利用がなぜ必要なのかをご記入ください。<br>
                                    例)一般公開情報[e-statや院内がん登録全国集計等]には含まれない○○の集計を行うため、全国がん登録情報が必要である。
                                </x-form-helper-text>
                            </div>
                            {{-- ここは添付ファイル --}}
                            @yield('section2-attachments')
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>

                {{-- 2. --}}
                <x-form-section>
                    <x-slot name="title">イ 倫理審査進捗状況</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <div>
                                <x-form-label for="2_ethical_review_status">{{config('app-ncc01.question-item-name.2_ethical_review_status')}}</x-form-label>
                                <x-form-error field="2_ethical_review_status"/>
                                <x-form-input-radio id="2_ethical_review_status_1" name="2_ethical_review_status" value="1" checked-value="{{$formValues->get('2_ethical_review_status')}}" :disabled="$isLocked">承認済み</x-form-input-radio>
                                <x-form-input-radio id="2_ethical_review_status_3" name="2_ethical_review_status" value="3" checked-value="{{$formValues->get('2_ethical_review_status')}}" :disabled="$isLocked">その他</x-form-input-radio>
                                <x-form-helper-text>{{ __('apply.format.2-1.helper-text.2.ethical-review') }}</x-form-helper-text>
                            </div>

                            <div class="block mt-4">
                                <x-form-label for="2_ethical_review_remark">その他を選択した場合の理由</x-form-label>
                                <x-form-error field="2_ethical_review_remark"/>
                                <x-form-input-textarea
                                    id="2_ethical_review_remark"
                                    class="block w-full"
                                    name="2_ethical_review_remark"
                                    rows="4"
                                    :disabled="$isLocked"
                                >{{$formValues->get('2_ethical_review_remark')}}</x-form-input-textarea>
                            </div>

                            <div class="block mt-4">
                                <x-form-label class="mt-0" for="attachment205">{{config('app-ncc01.attachment-type.205')}}</x-form-label>
                                <x-form-error field="attachment205"/>
                                <x-form-input-file
                                    id="attachment205"
                                    name="attachment205"
                                    type="file"
                                    class="block w-full"
                                    :current-file="$attachment205"
                                    :disabled="$isLocked"
                                />
                                @include('contents.apply.detail.common.notice_submit_file')
                            </div>

                            <div class="block mt-4">
                                <x-form-label for="2_ethical_review_board_name">{{config('app-ncc01.question-item-name.2_ethical_review_board_name')}}</x-form-label>
                                <x-form-error field="2_ethical_review_board_name"/>
                                <x-form-input
                                    id="2_ethical_review_board_name"
                                    type="text"
                                    class="block w-full"
                                    name="2_ethical_review_board_name"
                                    value="{{$formValues->get('2_ethical_review_board_name')}}"
                                    :disabled="$isLocked"
                                />
                            </div>

                            <div class="block mt-4">
                                <x-form-label for="2_ethical_review_board_code">{{config('app-ncc01.question-item-name.2_ethical_review_board_code')}}</x-form-label>
                                <x-form-error field="2_ethical_review_board_code"/>
                                <x-form-input
                                    id="2_ethical_review_board_code"
                                    type="text"
                                    class="block w-full"
                                    name="2_ethical_review_board_code"
                                    value="{{$formValues->get('2_ethical_review_board_code')}}"
                                    :disabled="$isLocked"
                                />
                            </div>

                            <div class="block mt-4">
                                <x-form-label for="2_ethical_review_board_date">{{config('app-ncc01.question-item-name.2_ethical_review_board_date')}}</x-form-label>
                                <x-form-error field="2_ethical_review_board_date"/>
                                <x-form-input
                                    id="2_ethical_review_board_date"
                                    type="text"
                                    name="2_ethical_review_board_date"
                                    class="block w-full"
                                    placeholder="YYYY-MM-DD"
                                    pattern="(?:19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])"
                                    title="有効な日付を YYYY-MM-DD 形式で入力してください（例：2024-03-15）"
                                    value="{{$formValues->get('2_ethical_review_board_date')}}"
                                    :disabled="$isLocked"
                                />
                            </div>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>

                {{-- ボタン --}}
                <x-action-area>
                    <a href="{{route('apply.detail.overview',['applyId'=>$id])}}">
                        <x-jet-secondary-button class="mr-2" type="button">戻る</x-jet-secondary-button>
                    </a>
                    {{-- 保存ボタン --}}
                    @if($canModifyApply)
                        <span class="inline-block {{ $isLocked ? 'opacity-50 pointer-events-none' : '' }}">
                            <livewire:save-apply-temporarily :is-locked="$isLocked" />
                        </span>
                    @endif
                </x-action-area>
            </x-buk-form>
        </div>
    </div>
</x-app-layout>
