<div>
    <x-form-label class="mt-0" for="">登録済み資料</x-form-label>
    @forelse($this->attachments701 as $array)
        <ul>
            <li class="text-sm" style="padding-left: 4px;"><a href="{{route('attachment.download',['id'=>$array['id']])}}">添付ID:{{$array['id']}} {{$array['name']}}</a></li>
        </ul>
    @empty
        （未登録）
    @endforelse

    {{-- 利用箇所 --}}
    <x-form-label class="mt-4" for="">利用環境の数</x-form-label>
    <x-form-helper-text>最大10箇所まで</x-form-helper-text>
    <div class="col-span-6 sm:col-span-4">
        <x-form-error field="number_of_environment"/>
        <x-form-input
            wire:model.lazy="numberOfEnvironment"
            id="number_of_environment"
            name="number_of_environment"
            type="number"
            min="1"
            max="10"
            class="block w-full"
            :disabled="$isLocked"/>
    </div>

    <x-form-label class="mt-4" for="">添付資料</x-form-label>
    <x-form-helper-text>利用施設ごとにファイルを添付してください。<br>「添付ファイル」画面からもアップロードすることができますが、アップロード後にファイルの種別（誓約書/委託契約書/研究計画書/集計案、等）を選択する必要があります。</x-form-helper-text>
    <x-form-helper-text> {{ __('apply.format.notice-upload-max-filesize.notice') }}</x-form-helper-text>
    <x-form-helper-text> {{ __('apply.format.notice-upload-max-filesize.size') }}</x-form-helper-text>

    @for ($i = 0; $i < $numberOfEnvironment; $i++)
        @php
            if (isset($this->attachments701[$i])) {
                $currentFile = $this->attachments701[$i];   //for form-input-file
                $currentFileId = $currentFile['id'];
            } else {
                $currentFile = null;
                $currentFileId = null;
            }
        @endphp

        <div class="col-span-6 mt-2">
            <x-form-error field="attachments701_{{$i}}"/>
            <span class="text-sm">[ 利用環境{{$i+1}} ] </span>
            <x-form-input-file
                id="attachments701_{{$i}}"
                name="attachments701[]"
                type="file"
                class="block w-full"
                :current-file="$currentFile"
                :disabled="$isLocked"
            />
            <x-form-input
                id="active_attachements701_{{$i}}"
                name="active_attachements701[]"
                type="hidden"
                value="{{$currentFileId}}" />
        </div>
    @endfor
</div>
