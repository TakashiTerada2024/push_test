@inject('attachmentTypes','Ncc01\Apply\Enterprise\Classification\AttachmentTypes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) に添付ファイル
        </h2>
    </x-slot>
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- ロック状態表示 --}}
            <x-lock-message :show="$isScreenLocked" message="この画面は現在ロックされています" />

            {{-- 添付ファイル追加 --}}
            @if($canModifyApply && !$isScreenLocked)
                @php
                    $isAllLocked = collect($attachmentLocks)->filter()->count() === collect($attachmentLocks)->count();
                @endphp
                @if(!$isAllLocked)
                    <x-buk-form action="{{route('attachment.apply.add',['id'=>$id])}}" method="post" has-files>
                        <x-jet-form-section submit="">
                            <x-slot name="title">添付ファイル追加</x-slot>
                            <x-slot name="description"></x-slot>
                            <x-slot name="form">
                                <div class="col-span-6">
                                    <x-form-input-file id="new" name="new" />
                                    <x-form-helper-text> 複数のファイルを添付される場合は1つずつアップロードしてください。</x-form-helper-text>
                                    <x-form-helper-text> 提出するファイルを選択し、「提出」ボタンを押してください。（アップロードされている状態では窓口組織に提出されません）</x-form-helper-text>
                                    <x-form-helper-text> 提出を取りやめたい場合は、「キャンセル」ボタンを押してください。</x-form-helper-text>
                                    <x-form-helper-text> 「削除」ボタンを押すとシステム内からファイルが削除されます。削除したファイルが必要な場合は、再度アップロードしてください。</x-form-helper-text>
                                    <x-form-helper-text> 複数のファイルをZIPファイルにまとめて提出することは控えてください。</x-form-helper-text>
                                </div>
                            </x-slot>
                            <x-slot name="actions">
                                <x-jet-button>保存</x-jet-button>
                            </x-slot>
                        </x-jet-form-section>
                    </x-buk-form>
                @else
                    {{-- ロック状態表示 --}}
                    <x-lock-message :show="true" message="すべての添付ファイル種別がロックされているため、新規ファイルを追加できません" />
                @endif
                <x-section-border/>
            @endif

            {{-- 添付ファイル一覧 --}}
            @forelse($attachments as $attachmentTypeId => $tmpArray)
                @if($attachmentTypeId)
                    <x-form-section>
                        <x-slot name="title">
                            <div class="flex items-center">
                                <span>種別: {{config('app-ncc01.attachment-type.'.$attachmentTypeId)}}</span>
                                @if(isset($attachmentLocks[$attachmentTypeId]) && $attachmentLocks[$attachmentTypeId])
                                    <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                                @endif
                            </div>
                        </x-slot>
                        <x-slot name="description"></x-slot>
                        <x-slot name="form">
                            <div class="col-span-6">
                                <table class="table table-fixed w-full bg-white">
                                <colgroup>
                                    <col style="width:6%;">
                                    <col style="width:20%;">
                                    <col style="width:15%;">
                                    <col style="width:20%;">
                                </colgroup>
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
                                            <td style="word-wrap: break-word;">{{$attachment['id']}}</td>
                                            <td class="py-2 " style="word-wrap: break-word;">
                                                <a href="{{route('attachment.download',['id'=>$attachment['id']])}}">{{$attachment['name']}}</a>
                                            </td>
                                            <td class="py-2 text-center" style="word-wrap: break-word;">{{$attachment['created_at']->setTimezone('Asia/Tokyo')->format('Y-m-d H:i')}}</td>
                                            <td>
                                                @if($canEditAttachment && !$isScreenLocked)
                                                    @if(!isset($attachmentLocks[$attachmentTypeId]) || !$attachmentLocks[$attachmentTypeId])
                                                        @if($isAdmin)
                                                            <x-button-attachment-admin :attachment="$attachment" :applyId="$id"></x-button-attachment-admin>
                                                        @else
                                                            <x-button-attachment-applicant :attachment="$attachment" :applyId="$id"></x-button-attachment-applicant>
                                                        @endif
                                                    @else
                                                        <span class="text-gray-400" title="ロックされているため操作できません">
                                                            @if($isAdmin)
                                                                <x-button-attachment-admin :attachment="$attachment" :applyId="$id" disabled></x-button-attachment-admin>
                                                            @else
                                                                <x-button-attachment-applicant :attachment="$attachment" :applyId="$id" disabled></x-button-attachment-applicant>
                                                            @endif
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-slot>
                    </x-form-section>
                @else
                    <x-buk-form action="{{route('attachment.apply.choose_type',['applyId'=>$id])}}" method="post" has-files>
                        <x-jet-form-section submit="">
                            <x-slot name="title">種別: 不明</x-slot>
                            <x-slot name="description"></x-slot>
                            <x-slot name="form">
                                <div class="col-span-6">
                                    <table class="table table-fixed w-full bg-white">
                                    <colgroup>
                                        <col style="width:6%;">
                                        <col style="width:20%;">
                                        <col style="width:15%;">
                                        <col style="width:15%;">
                                    </colgroup>
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ファイル名</th>
                                            <th>アップロード日時</th>
                                            @if($canModifyType && !$isScreenLocked)
                                            <th></th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($tmpArray as $attachment)
                                        <tr class="border-t">
                                            <td>{{$attachment['id']}}</td>
                                            <td class="py-2 "><a href="{{route('attachment.download',['id'=>$attachment['id']])}}">{{$attachment['name']}}</a></td>
                                            <td class="py-2 text-center">{{$attachment['created_at']->setTimezone('Asia/Tokyo')->format('Y-m-d H:i')}}</td>

                                            @if($canModifyType && !$isScreenLocked)
                                                {{-- hidden attachment id --}}
                                                <input type="hidden" name="attachment_id[{{$attachment['id']}}]" value="{{$attachment['id']}}" />

                                                <td class="py-2 text-right">
                                                    @php
                                                        $availableTypes = collect($attachmentTypes->listOfNameForApplicant())
                                                            ->reject(function($name, $key) use ($attachmentLocks) {
                                                                return isset($attachmentLocks[$key]) && $attachmentLocks[$key];
                                                            });
                                                    @endphp

                                                    @if($availableTypes->isEmpty())
                                                        <span class="text-gray-500 text-sm">選択可能な種別がありません</span>
                                                    @else
                                                        <x-form-input-select
                                                            class="text-xs"
                                                            :id="'type_'.$attachment['id']"
                                                            :name="'type['.$attachment['id'].']'"
                                                            :options="$availableTypes"
                                                        />
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </x-slot>

                            @if($canModifyType && !$isScreenLocked)
                            <x-slot name="actions">
                                @if($availableTypes->isEmpty())
                                    <x-jet-button disabled class="opacity-50 cursor-not-allowed">設定変更</x-jet-button>
                                @else
                                    @if(!isset($attachmentLocks[$attachmentTypeId]) || !$attachmentLocks[$attachmentTypeId])
                                        <x-jet-button>設定変更</x-jet-button>
                                    @else
                                        <x-jet-button disabled class="opacity-50 cursor-not-allowed">設定変更</x-jet-button>
                                    @endif
                                @endif
                            </x-slot>
                            @endif

                        </x-jet-form-section>
                    </x-buk-form>
                @endif
                <x-section-border/>
            @empty
                添付ファイルはありません。
            @endforelse
            {{-- 承認依頼ボタン --}}
            <x-action-area>
                <x-buk-form method="post" action="{{route('apply.start_checking_document',['applyId'=>$id])}}">
                    @if($canDisplayCheckingButton && $applyBaseInfo->getStatusId()===2 && !$isScreenLocked)
                        @if($canStartChecking)
                            <x-jet-danger-button type="submit">申出文書 承認依頼</x-jet-danger-button>
                        @else
                            <div>
                                <ul>
                                    <x-jet-danger-button type="button" disabled>申出文書 承認依頼</x-jet-danger-button>
                                    <li class="font-bold text-accent-500">※必須項目の入力が不足している、または添付ファイルが提出されていません</li>
                                    <li class="font-bold text-accent-500">
                                        ※必須項目の入力を<a class="text-main-500" href="{{ route('apply.detail.overview',['applyId'=>Request::route('applyId')]) }}">「申出概要」画面</a>へ移動して行ってください<br>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @else
                        <x-jet-danger-button type="button" disabled>申出文書 承認依頼</x-jet-danger-button>
                    @endif
                </x-buk-form>
            </x-action-area>
        </div>
    </div>
</x-app-layout>
