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
