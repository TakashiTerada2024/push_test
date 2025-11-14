@php /** @var \App\Http\Requests\Message\Apply\MessageBodyDto $dto */ @endphp
@inject('applyTypes','Ncc01\Apply\Enterprise\Classification\ApplyTypes')
提供依頼可否相談 内容
# 申出者、全国がん登録の利用種別
@if($dto->getApplyType()===99) (不明) @else {{$applyTypes->valueOfName($dto->getApplyType())}} @endif

# 調査研究について
## 研究課題名
{{$dto->getSubject()}}

## 研究期間
始期:{{$dto->getResearchPeriodStart()??'(記載なし)'}}
終期:{{$dto->getResearchPeriodEnd()??'(記載なし)'}}

## 調査研究の目的
{{$dto->getPurposeOfUse()}}
## 調査研究の方法
{{$dto->getResearchMethod()}}
## 全国がん登録情報の利用の必要性
{{$dto->getNeedToUse()}}

# その他、全国がん登録情報の利用に関する申出手続き等のご質問
{{$dto->getRemark()??'(記載なし)'}}

# 連絡先等
## 名前
{{$dto->getApplicantNameKana()}}
{{$dto->getApplicantName()}}

## 所属
{{$dto->getAffiliation()}}

## 電話番号
{{$dto->getApplicantPhoneNumber()}}
内線:{{$dto->getApplicantExtensionPhoneNumber()??'(記載なし)'}}

