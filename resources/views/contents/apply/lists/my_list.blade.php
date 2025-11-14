@inject('applyStatuses','Ncc01\Apply\Enterprise\Classification\ApplyStatuses')


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }} 一覧
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="pb-3" style="border: #1c7430 2px solid;padding: 10px;margin-bottom: 10px;">
                平素より当システムをご利用いただき、誠にありがとうございます。<br/>
                下記の日時におきまして、システムのメンテナンス作業を実施いたします。<br/>
                メンテナンスに伴い、下記の通り全システムを一時休止いたします。<br/>
                利用者の皆様にはご不便をおかけいたしますが、ご了承くださいますようお願い申し上げます。<br/>
                <br/>
                システムの休止日時<br/>
                2025年5月27日（火） 12:00 ～ 13:00（予定）<br/>
                2025年5月29日（木） 12:00 ～ 13:00（予定）<br/>
                <br/>
                ※作業の状況により終了時間が前後することがございます。<br/>
            </div>

            <div class="pb-3">
                <a href="{{route('apply.start')}}"><x-button-primary class="bg-main-500 text-md text-white hover:bg-base-700">新規 提供可否相談 作成</x-button-primary></a>
            </div>

            <table class="table-fixed w-full bg-white">
                <thead>
                    <tr class="border">
                        <th class="pl-4 py-2" width="10%">メッセージ</th>
                        <th class="px-4 py-2" width="10%">申出ID</th>
                        <th class="px-4 py-2" width="50%">研究課題</th>
                        <th class="px-4 py-2" width="20%">ステータス</th>
                        <th class="px-4 py-2" width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($applies as $arrApply)
                    <tr class="border">
                        <td class="pl-4 py-2 text-center">
                            <x-button-chat :apply-id="$arrApply['id']" :active="count($arrApply['unread_applicant_messages'])>0" />
                        </td>
                        <td class="px-4 py-2 text-center">{{$arrApply['id']}}</td>
                        <td class="px-4 py-2">{{$arrApply['subject']}}</td>
                        <td class="px-4 py-2 text-center">
                            {{-- データ提供可否 相談中 --}}
                            <x-label-status :status-id="$arrApply['status']" />
                        </td>
                        <td>
                            <x-menu-list :id="'menu-'.$arrApply['id']">
                                <li><a href="{{route('attachment.apply.show',['applyId'=>$arrApply['id']])}}" class="block px-4 py-3 hover:bg-base-300"><span class="text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg></span>添付ファイル</a></li>
                                @if($applyStatuses->whetherToShowApplyDetail($arrApply['status'],$arrApply['type_id']))
                                    <li><a href="{{route('apply.detail.overview',['applyId'=>$arrApply['id']])}}" class="block px-4 py-3 hover:bg-base-300"><span class="text-gray-500"> <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></span>詳細</a></li>
                                @endif
                                @if($arrApply['status']===$applyStatuses::PRIOR_CONSULTATION)
                                    <li>
                                        <a href="{{route('apply.start',['applyId'=>$arrApply['id']])}}" class="block px-4 py-3 hover:bg-base-300">
                                            <span class="text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </span>
                                            編集
                                        </a>
                                    </li>
                                @endif

                                @if($validateCanDisplayPdfOfApply->__invoke($arrApply['status'],$arrApply['type_id']))
                                    <li>
                                        <a href="{{route('pdf.apply.download',['applyId'=>$arrApply['id']])}}" class="block px-4 py-3 hover:bg-base-300">
                                            <span class="text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </span>
                                            申出文書
                                        </a>
                                    </li>
                                @endif

                                @if($arrApply['source_apply_history_count'] === 0 && $arrApply['status'] === $applyStatuses::ACCEPTED)
                                    @livewire('copy-apply', ['applyId' => $arrApply['id']])
                                @endif
                            </x-menu-list>
                        </td>
                    </tr>
                @empty
                    <tr class="border">
                        <td colspan="4">申出情報は未登録です。</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>
</x-app-layout>

