<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.9') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="post" action="" onsubmit="return false;">
                <x-form-section>
                    <x-slot name="title">{{config('app-ncc01.question-item-name.9_treatment_after_use')}}</x-slot>
                    <x-slot name="description">
                        利用終了後の処置（焼却、消去、返納、溶解又は裁断）について記載すること。<br>
                        なお、情報を利用する過程で作成される試行的な集計表や中間分析結果等の中間生成物の取扱いにおいても同様とする。
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6 sm:col-span-6">
                            <x-form-error field="9_treatment_after_use"/>
                            <x-form-input-textarea
                                id="9_treatment_after_use"
                                name="9_treatment_after_use"
                                class="block w-full"
                                rows="4"
                                :disabled="$isLocked"
                            >{{$formValues->get('9_treatment_after_use')}}</x-form-input-textarea>
                            <x-form-helper-text>
                                記載例：<br>
                                ・コンピュータ内の情報及び中間生成物： PC 上の中間生成物は速やかに削除する。<br>
                                ・研究利用目的データ移送用の USB メモリ：専用ソフトウェアを利用した内容削除を行う。<br>
                                ・試行的に作成した集計表や中間分析結果等の中間生成物の印刷物：規格を満たす室内のシュレッダにて裁断する
                            </x-form-helper-text>
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
