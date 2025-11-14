<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.6') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" has-files onsubmit="return false;">
                {{-- 1. --}}
                <x-form-section>
                    <x-slot name="title">{{config('app-ncc01.question-item-name.6_usage_period')}}</x-slot>
                    <x-slot name="description">
                        ※必要な限度の利用期間を記載すること
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <div>
                                <x-form-label for="">始期</x-form-label>
                                &nbsp;情報の提供を受けた日
                                <x-form-helper-text>
                                    利用期間の始期は、原則として「情報の提供を受けた日」となります。
                                </x-form-helper-text>
                            </div>

                            <div class="mt-2">
                                <x-form-label for="">終期</x-form-label>
                                <x-form-error field="6_usage_period_end"/>
                                <x-form-input
                                    id="6_usage_period_end"
                                    name="6_usage_period_end"
                                    value="{{$formValues->get('6_usage_period_end')}}"
                                    type="text"
                                    class="block w-full"
                                    placeholder="YYYY-MM-DD"
                                    pattern="(?:19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])"
                                    title="有効な日付を YYYY-MM-DD 形式で入力してください（例：2024-03-15）"
                                    :disabled="$isLocked"
                                />

                                <x-form-helper-text class="mt-2">
                                    利用期間の終期は、調査研究及びその成果の公表時期から逆算して必要十分な期間を設定します。なお、利用期間に、予定している調査研究の成果のすべての公表完了までを含みます。
                                </x-form-helper-text>

                                <x-form-helper-text class="mt-2">
                                    利用期間終了後は、提供を受けた情報は廃棄し、報告します。
                                </x-form-helper-text>

                                <x-form-helper-text class="mt-2">
                                    原則として「情報の提供を受けた日から 5 年を経過した日の属する年の 12 月 31 日」を期限としますが、提供に係る審議委員会が合理的な理由があると認める場合は、最長
                                    15 年まで認められます。
                                </x-form-helper-text>
                            </div>
                        </div>
                    </x-slot>
                </x-form-section>
                <x-section-border/>

                {{-- 2. --}}
                <x-form-section>
                    <x-slot name="title">{{config('app-ncc01.question-item-name.6_research_period')}}</x-slot>
                    <x-slot name="description"></x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <div>
                                <x-form-label for="">始期</x-form-label>

                                <x-form-error field="6_research_period_start"/>
                                <x-form-input
                                    id="6_research_period_start"
                                    name="6_research_period_start"
                                    value="{{$formValues->get('6_research_period_start')}}"
                                    type="text"
                                    class="block w-full"
                                    placeholder="YYYY-MM-DD"
                                    pattern="(?:19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])"
                                    title="有効な日付を YYYY-MM-DD 形式で入力してください（例：2024-03-15）"
                                    :disabled="$isLocked"
                                />
                            </div>

                            <div class="mt-2">
                                <x-form-label for="">終期</x-form-label>
                                <x-form-error field="6_research_period_end"/>
                                <x-form-input
                                    id="6_research_period_end"
                                    name="6_research_period_end"
                                    value="{{$formValues->get('6_research_period_end')}}"
                                    type="text"
                                    class="block w-full"
                                    placeholder="YYYY-MM-DD"
                                    pattern="(?:19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])"
                                    title="有効な日付を YYYY-MM-DD 形式で入力してください（例：2024-03-15）"
                                    :disabled="$isLocked"
                                />
                            </div>
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
