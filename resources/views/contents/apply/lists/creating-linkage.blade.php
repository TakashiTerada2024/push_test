@inject('applyStatuses','Ncc01\Apply\Enterprise\Classification\ApplyStatuses')
@inject('applyTypes','Ncc01\Apply\Enterprise\Classification\ApplyTypes')

<x-app-layout>
    <x-slot name="header">
        <x-slot name="headerclass">tab-display</x-slot>
        <div class="block mb-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ config('app-ncc01.system.title') }} 申出文書作成中（リンケージ）一覧
            </h2>
        </div>
        <a href="{{route('apply.lists.search')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">申出検索</x-button-tab></a>
        <a href="{{route('apply.lists.prior_consultation')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">事前相談</x-button-tab></a>
        <a href="{{route('apply.lists.creating_linkage')}}"><x-button-tab class="bg-gray-100">リンケージ</x-button-tab></a>
        <a href="{{route('apply.lists.creating_statistics')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">集計統計</x-button-tab></a>
        <a href="{{route('apply.lists.submitting')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">提出中</x-button-tab></a>
        <a href="{{route('apply.lists.accepted')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">応諾</x-button-tab></a>

    </x-slot>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <p class="text-2xl pb-1">未返信一覧</p>
            <table class="table-fixed w-full bg-white">
            <colgroup>
                <col style="width:5%;">
                <col style="width:17%;">
                <col style="width:7%;">
                <col style="width:14%;">
                <col style="width:20%;">
                <col style="width:8%;">
                <col style="width:10%;">
                <col style="width:8%;">
                <col style="width:6%;">
            </colgroup>
                <thead>
                <tr class="border">
                    <th colspan="2">メッセージ/メモ</th>
                    <th class="p-2">申出ID</th>
                    <th class="p-2">所属/氏名</th>
                    <th class="p-2">研究課題</th>
                    <th class="p-2">申出種別</th>
                    <th class="p-2">利用期間(終期)</th>
                    <th class="p-2">ステータス</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($appliesNotReplied as $row)
                <tr class="border">
                    <td>
                        <p class="text-center"><x-button-chat :apply-id="$row->id" :active="is_null($row->read_at)" /></p>
                    </td>
                    <td class="py-2">
                        <p>受信：{{format_datetime($row->created_at)}}</p>
                        <p class="pt-2">表示：{{format_datetime($row->read_at,'-')}}</p>
                    </td>
                    <td rowspan="2" class="p-2 text-center">
                        @if (empty($row->source_apply_id))
                            {{ $row->id }}
                        @else
                            @if($applyStatuses->whetherToShowApplyDetail($row->status,$row->type_id))
                                <span style="font-size:smaller;"><a href="{{route('apply.detail.overview',['applyId'=>$row->source_apply_id])}}" class="link-primary">{{ $row->source_apply_id }}</a>-></span><br />
                                {{ $row->id }}
                            @else
                                <span style="font-size:smaller;"><a href="#">{{ $row->source_apply_id }}</a>-></span><br />
                                {{ $row->id }}
                            @endif
                        @endif
                    </td>
                    <td rowspan="2" class="p-2">{{$row->affiliation}}<br>{{$row->{'10_applicant_name'} }}</td>
                    <td rowspan="2" class="p-2">{{$row->subject}}</td>
                    <td rowspan="2" >{{$applyTypes->valueOfName($row->type_id)}}</td>
                    <td rowspan="2" class="p-2 text-center">
                        {{$row->{'6_usage_period_end'} ??'終期記載なし'}}
                    </td>
                    <td rowspan="2" class="text-center"><x-label-status :status-id="$row->status" /></td>
                    <td rowspan="2" class="p-2">
                        <x-menu-list :id="'menu-'.$row->id">
                            @if($applyStatuses->whetherToShowApplyDetail($row->status,$row->type_id))
                                <li><a href="{{route('apply.detail.overview',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300"><span class="text-gray-500"> <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></span>詳細</a></li>
                            @endif

                            <li><a href="{{route('attachment.apply.show',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300"><span class="text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg></span>添付ファイル</a></li>

                            <li><a href="{{route('lock.management',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300">
                                <span class="text-gray-500">
                                    <x-icon-lock class="h-8 w-8 pr-2" />
                                </span>
                                ロック管理
                            </a></li>

                            @if($row->status===2 || $row->status===3)
                            <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                    <livewire:change-apply-type :apply-id="$row->id" :apply-subject="$row->subject" :apply-type-id="$row->type_id" />
                                </span>
                            </li>
                            @endif
                            @if(in_array($row->status,[$applyStatuses::CHECKING_DOCUMENT,$applyStatuses::SUBMITTING_DOCUMENT]))
                                <li>
                                    <a href="{{route('pdf.apply.download',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300">
                                <span class="text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </span>
                                        申出文書
                                    </a>
                                </li>
                            @endif

                            @if($row->status===3)
                            <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                <livewire:start-submitting-document :apply-id="$row->id" :apply-subject="$row->subject" />
                                </span>
                            </li>
                            <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                <livewire:remand-checking-document :apply-id="$row->id" :apply-subject="$row->subject" />
                                </span>
                            </li>
                            @endif
                            <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                    <livewire:cancel-apply :apply-id="$row->id" :apply-subject="$row->subject"/>
                                </span>
                            </li>
                        </x-menu-list>
                    </td>
                </tr>
                @livewire('memo-modal', ['applyId' => $row->id, 'memo' => $row->memo])
                @endforeach
                </tbody>
            </table>

            <x-section-border/>

        <p class="text-2xl pb-1">返信済一覧</p>
            <table class="table-fixed w-full bg-white">
            <colgroup>
            <col style="width:5%;">
                <col style="width:17%;">
                <col style="width:7%;">
                <col style="width:14%;">
                <col style="width:20%;">
                <col style="width:8%;">
                <col style="width:10%;">
                <col style="width:8%;">
                <col style="width:6%;">
            </colgroup>
                <thead>
                <tr class="border">
                    <th colspan="2">メッセージ/メモ</th>
                    <th class="p-2">申出ID</th>
                    <th class="p-2">所属/氏名</th>
                    <th class="p-2">研究課題</th>
                    <th class="p-2">申出種別</th>
                    <th class="p-2">利用期間(終期)</th>
                    <th class="p-2">ステータス</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
            @foreach($appliesReplied as $row)
                <tr class="border">
                    <td>
                        <p class="text-center"><x-button-chat :apply-id="$row->id" :active="is_null($row->read_at)" /></p>
                    </td>
                    <td class="py-2">
                        <p>受信：{{format_datetime($row->created_at)}}</p>
                        <p class="pt-2">表示：{{format_datetime($row->read_at,'-')}}</p>
                    </td>
                    <td rowspan="2" class="p-2 text-center">
                        @if (empty($row->source_apply_id))
                            {{ $row->id }}
                        @else
                            @if($applyStatuses->whetherToShowApplyDetail($row->status,$row->type_id))
                                <span style="font-size:smaller;"><a href="{{route('apply.detail.overview',['applyId'=>$row->source_apply_id])}}" class="link-primary">{{ $row->source_apply_id }}</a>-></span><br />
                                {{ $row->id }}
                            @else
                                <span style="font-size:smaller;"><a href="#">{{ $row->source_apply_id }}</a>-></span><br />
                                {{ $row->id }}
                            @endif
                        @endif
                    </td>
                    <td rowspan="2" class="p-2">{{$row->affiliation}}<br>{{$row->{'10_applicant_name'} }}</td>
                    <td rowspan="2" class="p-2">{{$row->subject}}</td>
                    <td rowspan="2">{{$applyTypes->valueOfName($row->type_id)}}</td>
                    <td rowspan="2" class="p-2 text-center">
                        {{$row->{'6_usage_period_end'} ??'終期記載なし'}}
                    </td>
                    <td rowspan="2" class="text-center"><x-label-status :status-id="$row->status" /></td>
                    <td rowspan="2" class="p-2">
                        <x-menu-list :id="'menu-'.$row->id">
                            @if($applyStatuses->whetherToShowApplyDetail($row->status,$row->type_id))
                                <li><a href="{{route('apply.detail.overview',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300"><span class="text-gray-500"> <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></span>詳細</a></li>
                            @endif

                            <li><a href="{{route('attachment.apply.show',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300"><span class="text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg></span>添付ファイル</a></li>

                            <li><a href="{{route('lock.management',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300">
                                <span class="text-gray-500">
                                    <x-icon-lock class="h-8 w-8 pr-2" />
                                </span>
                                ロック管理
                            </a></li>

                            @if($row->status===2 || $row->status===3)
                            <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                    <livewire:change-apply-type :apply-id="$row->id" :apply-subject="$row->subject" :apply-type-id="$row->type_id" />
                                </span>
                            </li>
                            @endif

                            @if(in_array($row->status,[$applyStatuses::CHECKING_DOCUMENT,$applyStatuses::SUBMITTING_DOCUMENT]))
                                <li>
                                    <a href="{{route('pdf.apply.download',['applyId'=>$row->id])}}" class="block px-4 py-3 hover:bg-base-300">
                                <span class="text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </span>
                                        申出文書
                                    </a>
                                </li>
                            @endif

                            @if($row->status===3)
                                <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                <livewire:start-submitting-document :apply-id="$row->id" :apply-subject="$row->subject" />
                                </span>
                                </li>
                                <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                <livewire:remand-checking-document :apply-id="$row->id" :apply-subject="$row->subject" />
                                </span>
                                </li>
                            @endif
                            <li>
                                <span class="block px-4 py-3 hover:bg-base-300">
                                    <livewire:cancel-apply :apply-id="$row->id" :apply-subject="$row->subject"/>
                                </span>
                            </li>
                        </x-menu-list>
                    </td>
                </tr>
                @livewire('memo-modal', ['applyId' => $row->id, 'memo' => $row->memo])
            @endforeach
                </tbody>
            </table>

    </div>
</x-app-layout>
