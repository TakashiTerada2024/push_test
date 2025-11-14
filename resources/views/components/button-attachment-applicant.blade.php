@props(['disabled' => false])

@if ($attachment['status'] == 1)
    <span class="py-2 text-md">アップロード済</span>
    @if ($validatePermissionSubmitAttachment->__invoke($applyId, $attachment['id']))
        <a href="{{ $disabled ? '#' : route('attachment.apply.submit',[ 'applyId' => $applyId , 'id' => $attachment['id'] ])}}" {{ $disabled ? 'onclick="return false;"' : '' }}>
            <x-jet-danger-button class="bg-accent-500 hover:bg-sub-500" :disabled="$disabled">提出</x-jet-danger-button>
        </a>
    @endif
    @if ($validatePermissionDeleteAttachment->__invoke(null, $attachment['id']))
    <a href="{{ $disabled ? '#' : route('attachment.apply.delete',[ 'applyId' => $applyId , 'id' => $attachment['id'] ])}}" {{ $disabled ? 'onclick="return false;"' : '' }}>
        <x-jet-danger-button class="bg-accent-500 hover:bg-sub-500" :disabled="$disabled">削除</x-jet-danger-button>
    </a>
    @endif
@elseif ($attachment['status'] == 2)
    <span class="py-2 text-md">提出済</span>
    <a href="{{ $disabled ? '#' : route('attachment.apply.cancel',[ 'applyId' => $applyId , 'id' => $attachment['id'] ])}}" {{ $disabled ? 'onclick="return false;"' : '' }}>
        <x-jet-danger-button class="bg-accent-500 hover:bg-sub-500" :disabled="$disabled">キャンセル</x-jet-danger-button>
    </a>
@elseif ($attachment['status'] == 3)
    <span class="py-2 text-md">承認済</span>
@endif
