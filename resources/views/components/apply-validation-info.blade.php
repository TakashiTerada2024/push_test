@php
/** @var \Specification\Validation\Contracts\ValidationResultInterface $validationResult */
@endphp
<div>
    {{-- ステータス表示 --}}
    <div class="p-2">
        @if($validationResult->isValid())
        <span class="bg-main-700 items-center p-2 text-white">記入状況:完了</span>
        @else
        <span class="bg-accent-700 items-center p-2 text-white">記入状況:未完了</span>
        @endif
    </div>

    {{-- エラーメッセージ --}}
    @if(!$validationResult->isValid())
        @if($hasAttachment)
        <span class="text-sm p-2 text-accent-500">「添付ファイル画面」から、提出するファイルを選択し、「提出」ボタンを押してください。</span>
        @endif

        @foreach($validationResult as $itemResult)
            @if(!$itemResult->isValid())
    <div class="p-2">
        <span class="font-bold text-accent-500">{{$itemResult->getSpecName()}}</span>
        <hr />
        {{$itemResult->getMessage()}}<br />
    </div>
            @endif
        @endforeach
    @endif


</div>
