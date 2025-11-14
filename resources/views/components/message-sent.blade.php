{{-- メッセージ表示枠:送信メッセージ --}}
@props(['message', 'authenticatedUser'])

<div class='grid grid-cols-6'>
    <div class="mt-3" style="grid-column: 2 / span 5;">
        <div class="px-4 py-5 bg-main-500 text-white sm:p-6 speech-bubble-sent">
            <span style="white-space:pre-wrap;">{{$message->getBody()}}</span>
            @if($message->getCreatedAt() != $message->getLastUpdatedAt())
            <br>[更新 :{{ $message->getLastUpdatedAt()->setTimezone('Asia/Tokyo') }}]
            @endif
        </div>

        @if($message->canEditUser($authenticatedUser) && !$message->isDeleted())
            <div class="text-right">
                <a href="#" onclick="Livewire.emitTo('edit-message', 'edit', '{{ $message->getId() }}', '{{ rawurlencode($message->getBody()) }}')" class="text-xs text-gray-700 underline">編集</a>
                <a href="#" onclick="Livewire.emitTo('delete-message', 'delete', '{{ $message->getId() }}')" class="text-xs text-gray-700 underline">削除</a>
            </div>
        @endif
        <div class="text-right">
            <span class="text-sm text-gray-700">{{$message->getCreatedAt()->setTimezone('Asia/Tokyo')}}
                                &nbsp;&nbsp;{{$message->getFromName($authenticatedUser)}}</span>
        </div>
    </div>
</div>
