{{-- 事務局(Secretariat)、スーパーユーザー(SuperAdmin)共通 --}}
@props(['disabled' => false])

@if ($attachment['status'] == 1)
    <span class="py-2 text-md">アップロード済</span>
    <a href="{{ $disabled ? '#' : route('attachment.apply.delete',[ 'applyId' => $applyId , 'id' => $attachment['id'] ])}}" {{ $disabled ? 'onclick="return false;"' : '' }}>
        <x-jet-danger-button class="bg-accent-500 hover:bg-sub-500" :disabled="$disabled">削除</x-jet-danger-button>
    </a>
@elseif ($attachment['status'] == 2)
    <span class="py-2 text-md">提出済</span>
    <a href="{{ $disabled ? '#' : route('attachment.apply.approve',[ 'applyId' => $applyId , 'id' => $attachment['id'] ])}}" {{ $disabled ? 'onclick="return false;"' : '' }}>
        <x-jet-danger-button class="bg-accent-500 hover:bg-sub-500" :disabled="$disabled">承認</x-jet-danger-button>
    </a>
    <a href="{{ $disabled ? '#' : route('attachment.apply.cancel',[ 'applyId' => $applyId , 'id' => $attachment['id'] ])}}" {{ $disabled ? 'onclick="return false;"' : '' }}>
        <x-jet-danger-button class="bg-accent-500 hover:bg-sub-500" :disabled="$disabled">キャンセル</x-jet-danger-button>
    </a>
@elseif ($attachment['status'] == 3)
    <span class="py-2 text-md">承認済</span>
    <a href="{{ $disabled ? '#' : route('attachment.apply.disapprove',[ 'applyId' => $applyId , 'id' => $attachment['id'] ])}}" {{ $disabled ? 'onclick="return false;"' : '' }}>
        <x-jet-danger-button class="bg-accent-500 hover:bg-sub-500" :disabled="$disabled">承認取消</x-jet-danger-button>
    </a>
@endif
