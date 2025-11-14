{{-- PDF用テンプレート --}}
@php
/** @var \Ncc01\Apply\Enterprise\Entity\ApplyDetail $applyDetail */
@endphp
@inject('prefectures','Ncc01\Common\Enterprise\Classification\Prefectures')
@inject('isRequired','Ncc01\Common\Enterprise\Classification\IsRequired')
@inject('icdTypes','Ncc01\Apply\Enterprise\Classification\IcdTypes')
@inject('sexes','Ncc01\Apply\Enterprise\Classification\Sexes')
@inject('rangeOfAgeTypes','Ncc01\Apply\Enterprise\Classification\RangeOfAgeTypes')
@inject('applyStatuses','Ncc01\Apply\Enterprise\Classification\ApplyStatuses')

<link rel="stylesheet" href="{{ public_path('css\style.css') }}" />

<div class="main @if($isApplicant && ($applyDetail->getStatus()->getValue() !== $applyStatuses::SUBMITTING_DOCUMENT)) sample @endif">

<!-- かがみ -->
<div class="pdf-page">
    <p>（@yield('apply-format-name','【様式の名称】')）</p>
    <p class="t-right">
        <ul class="t-right">
            @foreach ($applyDetail->getCopiedApplies() as $apply)
                <li>{{ $apply->submitted_at?(new \Carbon\Carbon($apply->submitted_at))->setTimezone('Asia/Tokyo')->format('Y年m月d日'):'（提出日:未定）' }}</li>
            @endforeach
            <li>{{ $applyDetail->getSubmittedAt()?->setTimezone('Asia/Tokyo')->format('Y年m月d日')??'（提出日:未定）' }}</li>
        </ul>
    </p>
    <p class="ls-half mgt3">@yield('apply-destination','【宛先】')</p>

    <p class="t-right mgt1">
        {{$applyDetail->getAffiliation()}}<br />
        {{$applyDetail->getApplicantName()}}<br />
        （押印省略）
    </p>
    <p class="t-center mgt3">@yield('apply-title','【申出件名】')</p>
    <p class="mgt5">@yield('apply-summary','【申出概要】以下文章。')</p>
</div>

<!-- 1.申出に係る情報の名称、2.情報の利用目的 -->
<div class="pdf-page">
    <p class="t-right"><span class="disp-ib enclose">別紙</span></p>
    <p class="t-center">@yield('apply-format-name','【様式の名称】')</p>

    <p class="mgt1">1　申出に係る情報の名称</p>
    <p class="pdl2 t-bold">@yield('information-type','【情報の種別】')</p>

    <ul class="pdl5">

        <li>@if($attachments[101]??false) ☑ @else ☐ @endif 添付：当該研究に係る同意取得説明文書</li>
        <li>@if($attachments[102]??false) ☑ @else ☐ @endif 添付：様式例第3-2号等（該当時）</li>
        <li>@if($attachments[103]??false) ☑ @else ☐ @endif 添付：実績を示す論文・報告書等</li>
    </ul>

    <p class="mgt1">2　情報の利用目的</p>

    <p class="pdl1">ア　利用目的及び必要性</p>

    {{-- 行政関係者かつ集計統計利用・リンケージ利用 --}}
    <p class="pdl2">利用目的及び必要性</p>
    <p class="pdl2">【利用目的】</p>
    <p class="pdl3">
        {!! nl2br(e($applyDetail->getPurposeOfUse())) !!}
    </p>
    <p class="pdl2">【必要性】</p>
    <p class="pdl3">
        {!! nl2br(e($applyDetail->getNeedToUse())) !!}
    </p>
    <ul class="pdl5">
        <li>@if($attachments[201]??false) ☑ @else ☐ @endif 添付：様式例第3-1号</li>
        <li>@if($attachments[202]??false) ☑ @elseif($attachments[203]??false) ☑ @else ☐ @endif 添付：委託の場合は委託契約書等又は様式例第4-1号</li>
        <li>@if($attachments[204]??false) ☑ @else ☐ @endif 添付：研究計画書等</li>
    </ul>

    {{-- 研究者かつ集計統計利用・リンケージ利用 --}}
    <p class="mgt1 pdl1">イ　倫理審査進捗状況
        <span class="disp-ib pdl3">{{-- 該当するものにcircleのクラスがつくようにしていただければ丸がつきます --}}

            <span class="@if($applyDetail->getEthicalReviewStatus()===1) circle @endif">承認済</span>
            　・　
            <span class="@if($applyDetail->getEthicalReviewStatus()===3) circle @endif">その他</span>
        </span>
    </p>
    <p class="pdl2">その他を選択した場合の理由：{!! nl2br(e($applyDetail->getEthicalReviewRemark())) !!}</p>
    <table class="pdl2">
        <tbody>
            <tr>
                <td>倫理審査委員会</td>
                <td class="pdl1">名称</td>
                <td class="pdl1">{{$applyDetail->getEthicalReviewBoardName()}}</td>
            </tr>
            <tr>
                <td></td>
                <td class="pd-half pdl1">承認番号</td>
                <td class="pd-half pdl1">{{$applyDetail->getEthicalReviewBoardCode()}}</td>
            </tr>
            <tr>
                <td></td>
                <td class="pdl1">承認年月日</td>
                <td class="pdl1">{{$applyDetail->getEthicalReviewBoardDate()?->setTimezone('Asia/Tokyo')?->format('Y-m-d')}}</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- 3.提供依頼申出者及び利用者 -->
<div class="pdf-page">
    <p class="mgt1">3　提供依頼申出者及び利用者について</p>
    <p class="pdl1">ア　提供依頼申出者の情報</p>
    <p class="pdl2">
        @if($applyDetail->getApplicantType()===2)
            <!-- 提供依頼申出者 法人の情報 -->
            代表者氏名：{{$applyDetail->getApplicantName()}}<br>
            法人その他の団体の名称：{{$applyDetail->getAffiliation()}}<br>
            法人その他の団体の住所：{{$applyDetail->getApplicantAddress()}}
        @else
            <!-- 提供依頼申出者 個人の情報 -->
            氏名：{{$applyDetail->getApplicantName()}}<br>
            住所：{{$applyDetail->getApplicantAddress()}}<br>
            生年月日：{{$applyDetail->getApplicantBirthday()?->setTimezone('Asia/Tokyo')?->format('Y-m-d')}}
        @endif
    </p>

    <p class="mgt1 pdl1">イ　利用者の範囲（氏名、所属、職名）</p>

    <ul class="pdl5">
        <li>@if($attachments[301]??false) ☑ @else ☐ @endif 添付：様式例第2-3号及び誓約書</li>
        <li>@if($attachments[302]??false) ☑ @elseif($attachments[303]??false) ☑ @else ☐ @endif 添付：@yield('apply-format-setion3-itaku','調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号')</li>
    </ul>

    @foreach($applyUsers??[] as $applyUser)
        <hr class="applyUserLine" />
        <dl class="applyUser">
            <dt>【氏名】</dt><dd>{{$applyUser['name']}}</dd>
            <dt>【所属】</dt><dd>{!! nl2br(e($applyUser['institution'])) !!}</dd>
            <dt>【職名】</dt><dd>{!! nl2br(e($applyUser['position'])) !!}</dd>
            <dt>【役割】</dt><dd class="last">{!! nl2br(e($applyUser['role'])) !!}</dd>
        </dl>
        @if ($loop->last)
            <hr class="applyUserLine" />
        @endif
    @endforeach
{{--    <p class="pdl1">全ての利用者分、表を追加すること。<br>所属機関が複数ある場合は、すべての所属機関及び所属する機関における職名又は立場を記載すること。</p>--}}
</div>

<!-- 4.利用する情報の範囲 -->
<div class="pdf-page">
    <p>4　利用する情報の範囲</p>

    <p class="pdl1">ア　診断年次<p>
    <p class="pdl2">{{$applyDetail->getYearOfDiagnoseStart()}}年次　～　{{$applyDetail->getYearOfDiagnoseEnd()}}年次</p>

    <p class="mgt1 pdl1">イ　地域</p>
    <p class="pdl2">
        @if ($applyDetail->isAllPrefectures($prefectures))
            {{-- prefがすべて選択されていた時 --}}
            ☑ 全国
        @else
            {{-- 選択されていないprefがあった時 --}}
            @foreach($prefectures as $id => $name)
                @if(in_array($id,($applyDetail->getAreaPrefectures()??[]))) ☑ @else ☐ @endif {{$name}}
            @endforeach
        @endif
    </p>

    <p class="mgt1 pdl1">ウ　がんの種類</p>
    <p class="pdl2">{{$icdTypes->value($applyDetail->getIdcType())}}</p>
    <p class="pdl2">{!! nl2br(e($applyDetail->getIdcDetail())) !!}</p>

    <p class="mgt1 pdl1">エ　生存確認情報（該当する方を囲むこと）</p>

    <table class="pdl3">
        <tbody>
            <tr>
                <td>①生存しているか死亡しているかの別</td>
                <td class="pdl2">
                    <span class="@if($applyDetail->getIsAliveRequired() === $isRequired::YES) circle @endif">要</span>
                    　・　
                    <span class="@if($applyDetail->getIsAliveRequired() === $isRequired::NO) circle @endif">不要</span>
                </td>
            </tr>
            <tr>
                <td>②生存を確認した直近の日又は死亡日</td>
                <td class="pdl2">
                    <span class="@if($applyDetail->getIsAliveDateRequired() === $isRequired::YES) circle @endif">要</span>
                    　・　
                    <span class="@if($applyDetail->getIsAliveDateRequired() === $isRequired::NO) circle @endif">不要</span>
                </td>
            </tr>
            <tr>
                <td>③死亡の原因</td>
                <td class="pdl2">
                    <span class="@if($applyDetail->getIsCauseOfDeathRequired() === $isRequired::YES) circle @endif">要</span>
                    　・　
                    <span class="@if($applyDetail->getIsCauseOfDeathRequired() === $isRequired::NO) circle @endif">不要</span>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="mgt1 pdl1">オ　属性的範囲</p>
    <p class="pdl2">性別　{{$sexes->value($applyDetail->getSex())}}</p>
    <p class="pdl2">{!! nl2br(e($applyDetail->getSexDetail())) !!}</p>
    <p class="pdl2">年齢　{{$rangeOfAgeTypes->value($applyDetail->getRangeOfAgeType())}}</p>
    <p class="pdl2">{!! nl2br(e($applyDetail->getRangeOfAgeDetail())) !!}</p>
</div>

<!-- 5.利用する登録情報及び調査研究方法 -->
<div class="pdf-page">
    <p class="mgt1">5　利用する登録情報及び調査研究方法</p>

    <p class="pdl1">ア　利用する登録情報</p>
    <ul class="pdl5">
        <li>@if($attachments[501]??false) ☑ @else ☐ @endif 添付：様式例2-1号別紙</li>
    </ul>

    <p class="mgt1 pdl1">イ　調査研究方法　（具体的に記載すること）</p>

    {{-- 研究者かつ集計統計利用・リンケージ利用 --}}
    <p class="pdl2">{!! nl2br(e($applyDetail->getResearchMethod())) !!}</p>
    <ul class="pdl5">
        <li>@if($attachments[502]??false) ☑ @else ☐ @endif 添付：集計表の様式案等</li>
    </ul>

</div>

<!-- 6.利用期間 ～ 10.その他 -->
<div class="pdf-page">
    <p>6　利用期間</p>
    <p class="pdl1">
        始期　情報の提供を受けた日<br>
        終期　{{$applyDetail->getUsagePeriodEnd()?->setTimezone('Asia/Tokyo')?->format('Y-m-d')}}
    </p>

    <p class="mgt1">7　利用場所、利用する環境、保管場所及び管理方法</p>
    <ul class="pdl5">
        <li>@if($attachments[701]??false) ☑ @else ☐ @endif 添付：安全管理措置 </li>
    </ul>

    <p class="pdl1">別紙添付資料に記載</p>

    <p class="mgt1">8　調査研究成果の公表方法及び公表予定時期</p>
    <p class="pdl1">{!! nl2br(e($applyDetail->getScheduledToBeAnnounced())) !!}</p>

    <p class="mgt1">9　情報等の利用後の処置</p>
    <p class="pdl1">{!! nl2br(e($applyDetail->getTreatmentAfterUse())) !!}</p>

    <p class="mgt1">10　その他</p>
    <p class="pdl2">{!! nl2br(e($applyDetail->getRemark()??'(なし)')) !!}</p>
    <p class="pdl2">事務担当者及び連絡先<br>
        氏名：{{$applyDetail->getClerkName()}}<br />
        連絡先住所：{{$applyDetail->getClerkContactAddress()}}<br />
        Eメール：{{$applyDetail->getClerkContactEmail()}}<br />
        電話番号：{{$applyDetail->getClerkContactPhoneNumber()}} {{$applyDetail->getClerkContactExtensionPhoneNumber()}}
    </p>

</diV>

</div>
