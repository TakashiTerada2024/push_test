@extends("contents.apply.detail.common.overview")

@section('apply-title')
    全国がん登録情報の提供について（申出）
@endsection

@section('apply-format-name')
    {{__('apply.format.name.linkage_21')}}
@endsection

@section('apply-destination')
    {!! __('apply.format.destination.linkage_21') !!}
@endsection

@section('information-type')
    全国がん登録情報（非匿名化情報）
@endsection

@section('apply-summary')
    @if ($applyBaseInfo->getSummary())
        {{ $applyBaseInfo->getSummary() }}
    @else
        標記について、がん登録等の推進に関する法律（平成25年法律第111号）第21条第3項の規定に基づき、別紙のとおり全国がん登録情報の提供の申出を行います。
    @endif
@endsection
