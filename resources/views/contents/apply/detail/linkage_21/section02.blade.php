@extends("contents.apply.detail.common.section02")

@section('section2-attachments')
<div class="mt-4">
    <x-form-label class="mt-0" for="attachment204">{{config('app-ncc01.attachment-type.204')}}</x-form-label>
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
@endsection
