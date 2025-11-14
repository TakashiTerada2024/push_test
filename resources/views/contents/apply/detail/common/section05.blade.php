<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.5') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" has-files onsubmit="return false;">
                {{-- 1. --}}
                <x-form-section>
                    <x-slot name="title">ア 利用する登録情報</x-slot>
                    <x-slot name="description">
                        ※必要な限度で別紙に◯をつけること<br/>
                        ※所定の書式に記入の上添付
                    </x-slot>

                    <x-slot name="aside">
                        @include('contents.apply.detail.sub.exp-modal.common-section05-501')
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6 sm:col-span-4">
                            <x-form-label class="mt-0" for="">{{config('app-ncc01.attachment-type.501')}}</x-form-label>
                            <x-form-error field="attachment501"/>
                            <x-form-input-file id="attachment501" name="attachment501" :current-file="$attachment501" class="block w-full" :disabled="$isLocked" />
                            @include('contents.apply.detail.common.notice_submit_file')
                        </div>
                    </x-slot>
                </x-form-section>
                <x-section-border />

                {{-- 2. --}}
                <x-form-section>
                    <x-slot name="title">イ {{config('app-ncc01.question-item-name.5_research_method')}}</x-slot>
                    <x-slot name="description">
                        ※具体的に記載すること<br/>
                        ※集計表の作成を目的とする調査研究の場合、アで指定する登録情報を利用して作成しようとしている集計表の様式案を添付する。<br/>
                        ※統計分析を目的とする調査研究の場合、実施を予定している統計分析手法並びに当該分析におけるアで指定する登録情報等の関係を具体的に記述する。
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6 sm:col-span-6">
                            <x-form-label class="mt-0" for="">{{config('app-ncc01.question-item-name.5_research_method')}}</x-form-label>
                            <x-form-error field="5_research_method"/>
                            <x-form-input-textarea
                                id="5_research_method"
                                name="5_research_method"
                                class="block w-full"
                                rows="6"
                                :disabled="$isLocked"
                            >{{$formValues->get('5_research_method')}}</x-form-input-textarea>
                            <x-form-helper-text>当該調査研究の中で、提供を希望する登録情報がどのように使われるのかの関係性が分かりやすく記載されていること。この関係性が不明確の場合は「必要な限度」と判断されません<div class=""></div></x-form-helper-text>
                            <x-form-helper-text class="mt-2">具体的に記載すること</x-form-helper-text>
                            <x-form-helper-text class="mt-2">統計分析を主な目的とする調査研究の場合、実施を予定している統計分析手法並びに当該分析におけるアで指定する登録情報等の関係を具体的に記述すること。</x-form-helper-text>

                            <x-form-label class="mt-4" for="">{{config('app-ncc01.attachment-type.502')}}</x-form-label>
                            <x-form-error field="attachment502"/>
                            <x-form-input-file id="attachment502" name="attachment502" :current-file="$attachment502" class="block w-full" :disabled="$isLocked" />
                            <x-form-helper-text class="mt-2">集計結果を公表する場合は提出が必須です。具体的な公表イメージを作成してください。<br>（統計解析がメインであっても、集計結果を公表する場合は添付必須です。併せて、公表イメージを添付いただけるようお願いします。）</x-form-helper-text>
                            @include('contents.apply.detail.common.notice_submit_file')

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
