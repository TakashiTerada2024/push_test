<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) 申出基本情報
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isLocked" />

            <x-buk-form method="POST" action="" onsubmit="return false;">
                <x-form-section>
                    <x-slot name="title">申出概要</x-slot>

                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-label for="10_clerk_contact_address" class="mt-2">カスタム鏡文</x-form-label>
                            <x-form-input-textarea
                                id="summary"
                                name="summary"
                                class="block w-full"
                                rows="3"
                                :disabled="$isLocked"
                            >{{ $formValues->get('summary') }}</x-form-input-textarea>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-section-border />

                {{-- ボタン --}}
                <x-action-area>
                    <a href="{{route('apply.detail.overview', ['applyId' => $id])}}">
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
