@inject('applyStatuses','Ncc01\Apply\Enterprise\Classification\ApplyStatuses')
@inject('applyTypes','Ncc01\Apply\Enterprise\Classification\ApplyTypes')

<x-app-layout>
    <x-slot name="header">
        <x-slot name="headerclass">tab-display</x-slot>
        <div class="block mb-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ config('app-ncc01.system.title') }} 申出検索
            </h2>
        </div>

        <a href="{{route('apply.lists.search')}}"><x-button-tab class="bg-gray-100">申出検索</x-button-tab></a>
        <a href="{{route('apply.lists.prior_consultation')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">事前相談</x-button-tab></a>
        <a href="{{route('apply.lists.creating_linkage')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">リンケージ</x-button-tab></a>
        <a href="{{route('apply.lists.creating_statistics')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">集計統計</x-button-tab></a>
        <a href="{{route('apply.lists.submitting')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">提出中</x-button-tab></a>
        <a href="{{route('apply.lists.accepted')}}"><x-button-tab class="hover:bg-main-500 hover:text-white">応諾</x-button-tab></a>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mb-6">
            <form action="{{ route('apply.lists.search') }}" method="GET">
                <div class="flex">
                    <input type="text" name="keyword" value="{{ $keyword }}" placeholder="メモ、ログイン者氏名、事務担当者 氏名" style="margin-right: 1.5rem;" class="w-full px-4 py-2 border rounded">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md h-[42px] text-sm font-medium hover:bg-gray-700 w-48">検索</button>
                </div>
            </form>
        </div>

        <p class="text-2xl pb-1">{{ empty($keyword) ? '最新の申出一覧' : '検索結果一覧（' . $count . '件）' }}</p>
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
                </tr>
            </thead>
            <tbody>
            @forelse($applies as $row)
                <tr class="border">
                    <td>
                        <p class="text-center"><x-button-chat :apply-id="$row->id" :active="isset($row->read_at) ? is_null($row->read_at) : true" /></p>
                    </td>
                    <td class="py-2">
                        <p>受信：{{isset($row->created_at) ? format_datetime($row->created_at) : '-'}}</p>
                        <p class="pt-2">表示：{{isset($row->read_at) ? format_datetime($row->read_at,'-') : '-'}}</p>
                    </td>
                    <td rowspan="2" class="p-2 text-center">
                        @if (empty($row->source_apply_id))
                            @if($applyStatuses->whetherToShowApplyDetail($row->status ?? 0,$row->type_id ?? 0))
                                <a href="{{route('apply.detail.overview',['applyId'=>$row->id])}}" class="link-primary">{{ $row->id }}</a>
                            @else
                                {{ $row->id }}
                            @endif
                        @else
                            <span style="font-size:smaller;">
                                @if($applyStatuses->whetherToShowApplyDetail($row->status ?? 0,$row->type_id ?? 0))
                                    <a href="{{route('apply.detail.overview',['applyId'=>$row->source_apply_id])}}" class="link-primary">{{ $row->source_apply_id }}</a>->
                                @else
                                    <a href="#">{{ $row->source_apply_id }}</a>->
                                @endif
                            </span><br />
                            @if($applyStatuses->whetherToShowApplyDetail($row->status ?? 0,$row->type_id ?? 0))
                                <a href="{{route('apply.detail.overview',['applyId'=>$row->id])}}" class="link-primary">{{ $row->id }}</a>
                            @else
                                {{ $row->id }}
                            @endif
                        @endif
                    </td>
                    <td rowspan="2" class="p-2">{{$row->affiliation ?? '-'}}<br>{{$row->{'10_applicant_name'} ?? '-'}}</td>
                    <td rowspan="2" class="p-2">{{$row->subject ?? '-'}}</td>
                    <td rowspan="2" class="p-2">{{isset($row->type_id) ? $applyTypes->valueOfName($row->type_id) : '-'}}</td>
                    <td rowspan="2" class="p-2 text-center">
                        {{$row->{'6_usage_period_end'} ?? '終期記載なし'}}
                    </td>
                    <td rowspan="2" class="text-center">
                        @if(($row->status ?? 0) == $applyStatuses::ACCEPTED)
                            <x-label-status :status-id="$row->status ?? 0" :destination-apply-id="$row->apply_id ?? 0" />
                        @else
                            <x-label-status :status-id="$row->status ?? 0" />
                        @endif
                    </td>
                </tr>
                @livewire('memo-modal', ['applyId' => $row->id, 'memo' => $row->memo ?? null])
            @empty
                <tr class="border">
                    <td colspan="8" class="p-4 text-center">
                        @if(empty($keyword))
                            表示できる申出はありません
                        @else
                            検索結果はありません
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
