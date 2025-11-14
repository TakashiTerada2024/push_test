{{-- PDF用テンプレート --}}
@php
/** @var \Ncc01\Apply\Enterprise\Entity\ApplyDetail $applyDetail */
@endphp

<link rel="stylesheet" href="{{ public_path('css\style.css') }}" />

<div class="pdf-page">
    <p>（様式第2-1号申出21_3）</p>
    <p class="t-right">○○年○○月○○日</p>
    <p class="ls-half mgt3">厚生労働大臣</p>
    <p>○○　○○　殿</p>

    <p class="t-right mgt1">{{$applyDetail->getApplicantName()}}</p>
    <p class="t-center mgt3">全国がん登録情報の提供について（申出）</p>
    <p class="mgt5">標記について、がん登録等の推進に関する法律（平成25年法律第111号）第21条第3項の規定に基づき、別紙のとおり全国がん登録情報の提供の申出を行います。</p>
</div>

<div class="pdf-page">
    <p class="t-right"><span class="disp-ib enclose">別紙</span></p>
    <p class="t-center">様式第2_1号申出21_3</p>

    <p class="mgt1">1　申出に係る情報の名称</p>

    {{-- 行政関係者・研究者かつ集計統計利用 --}}
    <p class="pdl2 t-bold">匿名化が行われた全国がん登録情報</p>

    {{-- 行政関係者かつリンケージ利用 --}}
    <p class="pdl2 t-bold">全国がん登録情報（非匿名化情報）</p>

    {{-- 研究者かつリンケージ利用 --}}
    <p class="pdl2 t-bold">全国がん登録情報（非匿名化情報）</p>
    <ul class="pdl5"> {{-- チェックのあるなしの出し分けは ☑ と ☐ を出し分けるようにしていただけると嬉しゅうございます --}}
        <li>☐ 添付：同意取得説明文書、同意書の見本等</li>
        <li>☐ 添付：様式第3-2号等（該当時）</li>
        <li>☐ 添付：実績を示す論文・報告書等</li>
    </ul>

    <p class="mgt1">2　情報の利用目的</p>

    <p class="pdl1">ア　利用目的及び必要性</p>

    {{-- 行政関係者かつ集計統計利用・リンケージ利用 --}}
    <p class="pdl2">利用目的及び必要性</p>
    <ul class="pdl5">
        <li>☐ 添付：様式第3-1号</li>
        <li>☐ 添付：委託の場合は委託契約書等又は様式第4-1号</li>
    </ul>

    {{-- 研究者かつ集計統計利用・リンケージ利用 --}}
    <ul class="pdl5">
        <li>☐ 添付：研究計画書等</li>
    </ul>

    {{-- すべてに共通？スペースあったので表示するのかなと思ったんですが違ったら消してください --}}
    <p class="pdl2">
        {{$applyDetail->getPurposeOfUse()}}<br>
        {{$applyDetail->getNeedToUse()}}
    </p>

    {{-- 研究者かつ集計統計利用・リンケージ利用 --}}
    <p class="mgt1 pdl1">イ　倫理審査進捗状況
        <span class="disp-ib pdl3">{{-- 該当するものにcircleのクラスがつくようにしていただければ丸がつきます --}}
            <span class="circle">承認済</span>
            　・　
            <span>審査中</span>
            　・　
            <span>その他</span>
        </span>
    </p>
    <p class="pdl2">その他を選択した場合の理由：{{$applyDetail->getEthicalReviewRemark()}}</p>
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
                <td class="pdl1">{{$applyDetail->getEthicalReviewBoardDate()}}</td>
            </tr>
        </tbody>
    </table>

    <p class="mgt1">3　利用者の範囲（氏名、所属、職名、役割）</p>
    <ul class="pdl5">
        <li>☐ 添付：様式例第2-3号及び誓約書</li>
        <li>☐ 添付：調査研究の一部を委託している場合は、委託契約書又は様式例第4-2号</li>
    </ul>

    <table class="border">
        <tbody>
            <tr>
                <td class="heavy">氏名</td>
                <td class="heavy">所属</td>
                <td class="heavy">職名</td>
                <td class="heavy">役割</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <p class="pdl1">全ての利用者分、表を追加すること。<br>所属機関が複数ある場合は、すべての所属機関及び所属する機関における職名又は立場を記載すること。</p>
</div>

<div class="pdf-page">
    <p>4　利用する情報の範囲</p>

    <p class="pdl1">ア　診断年次<p>
    <p class="pdl2">{{$applyDetail->getYearOfDiagnoseStart()}}　～　{{$applyDetail->getYearOfDiagnoseEnd()}}</p>

    <p class="mgt1 pdl1">イ　地域</p>
    <p class="pdl2">{{-- $applyDetail->getAreaPrefectures() --}}</p>{{-- arrayの表示がうまくできなかったのでコメントアウトしています --}}

    <p class="mgt1 pdl1">ウ　がんの種類</p>
    <p class="pdl2">{{$applyDetail->getIdcType()}}</p>

    <p class="mgt1 pdl1">エ　生存確認情報（該当する方を囲むこと）</p>
    <p class="pdl2">
        <span class="circle">要</span>
        　・　
        <span>不要</soan>
    </p>
    <table class="pdl3">
        <tbody>
            <tr>
                <td>①生存しているか死亡しているかの別</td>
                <td class="pdl2">
                    <span class="circle">要</span>
                    　・　
                    <span>不要</soan>
                </td>
            </tr>
            <tr>
                <td>②生存を確認した直近の日又は死亡日</td>
                <td class="pdl2">
                    <span class="circle">要</span>
                    　・　
                    <span>不要</soan>
                </td>
            </tr>
            <tr>
                <td>③死亡の原因</td>
                <td class="pdl2">
                    <span class="circle">要</span>
                    　・　
                    <span>不要</soan>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="mgt1 pdl1">オ　属性的範囲</p>
    <p class="pdl2">
        性別　{{$applyDetail->getSex()}}<br>
        年齢　
    </p>

    <p class="mgt1">5　利用する登録情報及び調査研究方法</p>

    <p class="pdl1">ア　利用する登録情報</p>
    <p class="pdl2">必要な限度で<b><u>別紙に○</u></b>をつけること</p>

    <p class="mgt1 pdl1">イ　調査研究方法　（具体的に記載すること）</p>

    {{-- 行政関係者かつ集計統計利用・リンケージ利用 --}}
    <p class="pdl2"></p>

    {{-- 研究者かつ集計統計利用・リンケージ利用 --}}
    <ul class="ti">
        <li>☐ 添付：集計表の様式案等</li>
    </ul>
    <p class="mgt1">※集計表の作成を目的とする調査研究の場合<br>
    アで指定する登録情報等を利用して作成しようとしている集計表の様式案を添付する。</p>
    <p>※統計分析を目的とする調査研究の場合<br>
    実施を予定している統計分析手法並びに当該分析におけるアで指定する登録情報等の関係を具体的に記述する。</p>

</div>

<div class="pdf-page">
    <p>6　利用期間</p>
    <p class="pdl1">
        始期　情報の提供を受けた日<br>
        終期　{{$applyDetail->getUsagePeriodEnd()}}
    </p>

    <p class="mgt1">7　利用場所、利用する環境、保管場所及び管理方法</p>
    <p class="pdl1">別紙添付資料を参照</p>

    <p class="mgt1">8　調査研究成果の公表方法及び公表予定時期</p>
    <p class="pdl1">{{$applyDetail->getScheduledToBeAnnounced()}}</p>

    <p class="mgt1">9　情報等の利用後の処置</p>
    <p class="pdl1">{{$applyDetail->getTreatmentAfterUse()}}</p>

    <p class="mgt1">10　その他</p>
    <p class="pdl1">事務担当者及び連絡先等を記載する。<br>他、必要事項があれば記載する。</p>

    <p class="mgt1">事務担当者及び連絡先<br>
    氏名：</br>
    連絡先：</p>

    <p class="mgt1">提供依頼申出者情報<br>
    氏名：{{$applyDetail->getApplicantName()}}<br>
    住所：〒{{$applyDetail->getApplicantAddress()}}<br>
    生年月日：{{$applyDetail->getApplicantBirthday()}}</p>
</diV>
