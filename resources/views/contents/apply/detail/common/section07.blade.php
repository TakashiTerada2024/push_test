<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) {{ config('app-ncc01.question-section-name.7') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" has-files onsubmit="return false;">

                {{-- 1. --}}
                <x-form-section>
                    <x-slot name="title">{{config('app-ncc01.attachment-type.701')}}</x-slot>
                    <x-slot name="description">
                        ※利用場所が複数ある場合は、複数添付すること。<br />
                        ※添付ファイルは、情報を利用する場所、施設等の名称とすること。<br />
                        @yield('section07-701-file')
                    </x-slot>

                    <x-slot name="aside">
                        @yield('section07-701-modal')
                    </x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <livewire:attachment701 :attachments701="$attachments701" :is-locked="$isLocked"/>
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
