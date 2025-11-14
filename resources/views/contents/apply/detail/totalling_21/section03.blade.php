@extends("contents.apply.detail.common.section03")

@section('section3-attachments')
<x-form-section>
    <x-slot name="title">添付文書</x-slot>
    <x-slot name="description"><span class="text-red-800">[必須]</span></x-slot>
    <x-slot name="aside">
        @include('contents.apply.detail.sub.exp-modal.totalling_21-section03-301')
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-form-label class="mt-0" for="file1">{{config('app-ncc01.attachment-type.301')}}</x-form-label>
            <x-form-error field="attachment301"/>
            <x-form-input-file
                id="attachment301"
                name="attachment301"
                type="file"
                class="block w-full"
                :current-file="$attachment301"
                :disabled="$isLocked"
            />
            @include('contents.apply.detail.common.notice_submit_file')
        </div>
    </x-slot>
</x-form-section>

<x-section-border/>

<x-form-section>
    <x-slot name="title">添付文書</x-slot>
    <x-slot name="description"><span class="text-blue-800">[調査研究を委託している場合のみ]</span></x-slot>
    <x-slot name="aside">
        @include('contents.apply.detail.sub.exp-modal.totalling_21-section03-301-2')
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-form-label class="mt-2" for="file2">{{config('app-ncc01.attachment-type.302')}}</x-form-label>
            <x-form-error field="attachment302"/>
            <x-form-input-file
                id="attachment302"
                name="attachment302"
                type="file"
                class="block w-full"
                :current-file="$attachment302"
                :disabled="$isLocked"
            />
            @include('contents.apply.detail.common.notice_submit_file')

            <x-form-label class="mt-2" for="file2">{{config('app-ncc01.attachment-type.303')}}</x-form-label>
            <x-form-error field="attachment303"/>
            <x-form-input-file
                id="attachment303"
                name="attachment303"
                type="file"
                class="block w-full"
                :current-file="$attachment303"
                :disabled="$isLocked"
            />
            @include('contents.apply.detail.common.notice_submit_file')
        </div>
    </x-slot>
</x-form-section>

@endsection
