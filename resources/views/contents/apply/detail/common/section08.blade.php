<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.8') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />
            <x-buk-form method="POST" action="" onsubmit="return false;">
                <x-form-section>
                    <x-slot name="title">{{config('app-ncc01.question-item-name.8_scheduled_to_be_announced')}}</x-slot>
                    <x-slot name="description">
                        複数の媒体で公表予定の場合は、公表予定時期を含めてすべて記載すること。<br/>
                        「6 利用期間の記載内容」と矛盾しないこと。<br/>
                        （公表時期が確定していない場合には、研究内容や研究期間を踏まえ、適当な公表予定時期が記載されていれば可）
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6 sm:col-span-4">
                            <x-form-error field="8_scheduled_to_be_announced"/>
                            <x-form-input-textarea
                                id="8_scheduled_to_be_announced"
                                name="8_scheduled_to_be_announced"
                                class="block w-full"
                                rows="6"
                                :disabled="$isLocked"
                                placeholder="学会発表：20XX年〇月頃
ガイドライン掲載：20XX年〇月頃
ウェブサイト掲載：20XX年〇月頃
論文公表：20XX年〇月頃
書籍：20XX年〇月頃
その他（自由記載）：20XX年〇月頃"
                            >{{$formValues->get('8_scheduled_to_be_announced')}}</x-form-input-textarea>
                            <x-form-helper-text>
                            記載例：<br>
                                2023 年○月予定 ○○学会にて発表<br>
                                2023 年○月予定 ○○ガイドライン掲載、同時にウェブサイトに掲載<br>
                                2022 年○月予定 論文公表予定
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
