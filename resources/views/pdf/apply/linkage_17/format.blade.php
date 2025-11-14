@extends('pdf.apply.common.format')
@section('apply-title')
    全国がん登録情報の提供について（申出）
@endsection

@section('apply-format-name')
    {{__('apply.format.name.linkage_17')}}
@endsection

@section('apply-destination')
    {!! __('apply.format.destination.linkage_17') !!}
@endsection

@section('information-type')
    全国がん登録情報（非匿名化情報）
@endsection

@section('apply-summary')
    @if (empty($applyDetail->getSummary()))
        標記について、がん登録等の推進に関する法律（平成25年法律第111号）（17条、第21条第1項、第21条第2項）の規定に基づき、別紙のとおり全国がん登録情報の提供の申出を行います。
    @else
        {!! nl2br(e($applyDetail->getSummary())) !!}
    @endif
@endsection

@section('apply-format-setion3-itaku')
調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号
@endsection
