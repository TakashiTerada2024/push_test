{{-- メッセージ表示枠:受信メッセージ --}}
@props(['message', 'authenticatedUser'])

<div class='grid grid-cols-6'>
    <div class="mt-3" style="grid-column: 1 / span 5;">
        <div class="px-4 py-5 bg-white sm:p-6 speech-bubble-recieved">
            <span style="white-space:pre-wrap;">{{$message->getBody()}}</span>
            @if($message->getCreatedAt() != $message->getLastUpdatedAt())
                <br>[更新 :{{ $message->getLastUpdatedAt()->setTimezone('Asia/Tokyo') }}]
            @endif
        </div>
        <div class="text-left">
            <span class="text-sm text-gray-700">{{$message->getCreatedAt()->setTimezone('Asia/Tokyo')}}
                                &nbsp;&nbsp;{{$message->getFromName($authenticatedUser)}}</span>
        </div>
    </div>
</div>
