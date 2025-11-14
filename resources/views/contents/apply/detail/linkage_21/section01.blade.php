<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.1') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" has-files onsubmit="return false;">
                {{-- 情報の名称 --}}
                <x-form-section>
                    <x-slot name="title">情報の名称</x-slot>
                    <x-slot name="description">※変更不可</x-slot>
                    <x-slot name="form">
                        <div class="col-span-6">
                            全国がん登録情報（非匿名化情報）
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>

                {{-- 添付文書 --}}
                <x-form-section>
                    <x-slot name="title">添付文書</x-slot>
                    <x-slot name="description"></x-slot>
                    <x-slot name="aside">
                        @include('contents.apply.detail.sub.exp-modal.linkage_21-section01-101')
                    </x-slot>
                    <x-slot name="form">
                        <div class="col-span-6 sm:col-span-6">
                            <x-form-label for="attachment101" class="text-base">{{config('app-ncc01.attachment-type.101')}}</x-form-label>
                            <x-form-error field="attachment101" />
                            <x-form-input-file
                                id="attachment101"
                                name="attachment101"
                                type="file"
                                class="block w-full"
                                :current-file="$attachment101"
                                :disabled="$isLocked"
                            />
                            <x-form-helper-text class="mt-2">がんに係る調査研究のために全国がん登録情報が提供されることについて、書面等の形式で、調査研究対象者の方から適切に同意を得ていることが分かる書類を添付すること。</x-form-helper-text>
                            @include('contents.apply.detail.common.notice_submit_file')

                            <x-section-border/>

                            <x-form-label for="attachment102" mark="applicable" class="text-base">{{config('app-ncc01.attachment-type.102')}}</x-form-label>
                            <x-form-error field="attachment102" />
                            <x-form-input-file
                                id="attachment102"
                                name="attachment102"
                                type="file"
                                class="block w-full"
                                :current-file="$attachment102"
                                :disabled="$isLocked"
                            />
                            @include('contents.apply.detail.common.notice_submit_file')

                            <x-section-border/>

                            <x-form-label for="attachment103" mark="require" class="text-base">{{config('app-ncc01.attachment-type.103')}}</x-form-label>
                            <x-form-error field="attachment103" />
                            <x-form-input-file
                                id="attachment103"
                                name="attachment103"
                                type="file"
                                class="block w-full"
                                :current-file="$attachment103"
                                :disabled="$isLocked"
                            />
                            <x-form-helper-text class="mt-2">提供依頼申出者が、がんに係る調査研究であってがん医療の質の向上等に資するものの実績を 2 以上有することを証明する書類（例：学術論文、報告書等）を添付すること。
                            </x-form-helper-text>
                            @include('contents.apply.detail.common.notice_submit_file')
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
                        <span class="inline-block {{ $isLocked ? 'opacity-50 pointer-events-none' : '' }}">
                            <livewire:save-apply-temporarily :is-locked="$isLocked" />
                        </span>
                    @endif
                </x-action-area>
            </x-buk-form>
        </div>
    </div>
</x-app-layout>
