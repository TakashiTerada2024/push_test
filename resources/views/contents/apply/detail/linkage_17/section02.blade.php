@extends("contents.apply.detail.common.section02")

@section('section2-attachments')
    <div class="mt-4">
        <x-form-label class="mt-0" for="attachment201">{{config('app-ncc01.attachment-type.201')}}</x-form-label>
        <x-form-error field="attachment201"/>
        <x-form-input-file
            id="attachment201"
            name="attachment201"
            type="file"
            class="block w-full"
            :current-file="$attachment201"
            :disabled="$isLocked"
        />
        @include('contents.apply.detail.common.notice_submit_file')
    </div>

    <div class="mt-4">
        <x-form-label class="mt-0" for="attachment204" mark="applicable">{{config('app-ncc01.attachment-type.204')}}</x-form-label>
        <x-form-error field="attachment204"/>
        <x-form-input-file
            id="attachment204"
            name="attachment204"
            type="file"
            class="block w-full"
            :current-file="$attachment204"
            :disabled="$isLocked"
        />
        <x-form-helper-text>当該研究計画に対する倫理審査用の研究計画書の写しも可。</x-form-helper-text>
        @include('contents.apply.detail.common.notice_submit_file')
    </div>

    {{-- 後方互換。登録済みの場合のみ表示する attachment202/attachment203 --}}
    @if (!empty($attachment202))
    <div class="mt-4">
        <x-form-label class="mt-0" for="attachment202">{{config('app-ncc01.attachment-type.202')}}</x-form-label>
        <x-form-error field="attachment202"/>
        <x-form-input-file
            id="attachment202"
            name="attachment202"
            type="file"
            class="block w-full"
            :current-file="$attachment202"
            :disabled="$isLocked"
        />
        @include('contents.apply.detail.common.notice_submit_file')
    </div>
    @endif

    @if (!empty($attachment203))
    <div class="mt-4">
        <x-form-label class="mt-0" for="attachment203">{{config('app-ncc01.attachment-type.203')}}</x-form-label>
        <x-form-error field="attachment203"/>
        <x-form-input-file
            id="attachment203"
            name="attachment203"
            type="file"
            class="block w-full"
            :current-file="$attachment203"
            :disabled="$isLocked"
        />
        @include('contents.apply.detail.common.notice_submit_file')
    </div>
    @endif

@endsection
