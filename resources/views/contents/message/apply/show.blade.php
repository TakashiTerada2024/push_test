{{-- 申出に紐付いたメッセージの履歴を表示する画面 --}}
@php
/** @var \Ncc01\User\Application\OutputBoundary\AuthenticatedUserInterface $authenticatedUser */

@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) に関するメッセージ履歴
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- メッセージ送信 --}}
            <x-buk-form action="{{route('message.apply.send',['applyId'=>$id])}}">
                <div class="px-4 py-5 bg-white sm:p-6 sm:rounded-md">
                    <div class="text-center">
                        <x-form-input-textarea type="text" name="message_body" id="message_body" rows="4" class="w-full" placeholder="メッセージを入力..."/>
                    </div>

                    <div class="block w-full text-center">
                        <x-button-primary id="send-button" type="submit" class="text-md bg-accent-500 px-6 hover:bg-base-700">送信</x-button-primary>
                    </div>
                </div>
            </x-buk-form>

            <x-section-border />

            {{-- メッセージ表示 --}}
            <div class="px-4 py-3 sm:px-6 bg-white sm:rounded-md bg-base-500">
                @php /** @var \Ncc01\Messaging\Application\OutputData\Message $message */ @endphp
                @forelse($messages as $message)
                    @if($message->isSentByLoginUser($authenticatedUser))
                        {{-- ログイン者自身が送ったメッセージ --}}
                        <x-message-sent :message="$message" :authenticatedUser="$authenticatedUser"></x-message-sent>
                    @else
                        {{-- 相手が送信したメッセージ --}}
                        <x-message-recieved :message="$message" :authenticatedUser="$authenticatedUser"></x-message-recieved>
                    @endif
                @empty
                    <p class="text-center text-gray-700 p-6">メッセージはありません。</p>
                @endforelse
            </div>
            @livewire('edit-message')
            @livewire('delete-message')
        </div>
</x-app-layout>

