@inject('attachmentTypes','Ncc01\Apply\Enterprise\Classification\AttachmentTypes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) 事務局送付資料
        </h2>
    </x-slot>
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- 事務局資料追加 --}}
            @if($canSendAttachmentBySecretariat->__invoke($id))
            <x-buk-form action="{{route('attachment.apply.secretariat.add',['id'=>$id])}}" method="post" has-files>
                <x-jet-form-section submit="">
                    <x-slot name="title">資料追加</x-slot>
                    <x-slot name="description"></x-slot>
                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-error field="new" />
                            <x-form-input-file multiple="multiple" id="new" name="new[]" />
                            <x-form-helper-text> {{ __('apply.format.notice-upload-max-filesize.size') }}</x-form-helper-text>
                            <x-form-helper-text> {{ __('apply.format.notice-upload-multiple-file.max-number') }}</x-form-helper-text>
                        </div>
                    </x-slot>
                    <x-slot name="actions">
                        <x-jet-button>保存</x-jet-button>
                    </x-slot>
                </x-jet-form-section>
            </x-buk-form>
            <x-section-border/>
            @endif

            {{-- 添付ファイル一覧 --}}
            @forelse($attachments as $attachmentTypeId => $tmpArray)
                @if($attachmentTypeId)
                    <x-form-section>

                        <x-slot name="title">種別: {{config('app-ncc01.attachment-type.'.$attachmentTypeId)}}</x-slot>
                        <x-slot name="description"></x-slot>
                        <x-slot name="form">
                            <div class="col-span-6">
                                <table class="table w-full bg-white">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ファイル名</th>
                                        <th>アップロード日時</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tmpArray as $attachment)
                                        <tr class="border-t">
                                            <td>{{$attachment['id']}}</td>
                                            <td class="py-2 "><a href="{{route('attachment.download',['id'=>$attachment['id']])}}">{{$attachment['name']}}</a></td>
                                            <td class="py-2 text-center">{{$attachment['created_at']->setTimezone('Asia/Tokyo')->format('Y-m-d H:i')}}</td>
                                            <td>
                                                @if($isAdmin)
                                                    <x-button-attachment-admin :attachment="$attachment" :applyId="$id"></x-button-attachment-admin>
                                                @else
                                                    <x-button-attachment-applicant :attachment="$attachment" :applyId="$id"></x-button-attachment-applicant>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-slot>
                    </x-form-section>
                @endif
                <x-section-border/>
            @empty
                事務局送付資料はありません。
            @endforelse

        </div>
    </div>

</x-app-layout>
