@inject('rangesOfAgeType','Ncc01\Apply\Enterprise\Classification\RangeOfAgeTypes')

@extends("contents.apply.detail.common.section04")

@section('age_range_detail')
<x-form-label class="mt-0" for="">{{config('app-ncc01.question-item-name.4_range_of_age_type')}}</x-form-label>
<x-form-error field="4_range_of_age_type"/>
<x-form-input-radios
    id="4_range_of_age_type"
    name="4_range_of_age_type"
    :options="$rangesOfAgeType"
    :checked-value="$formValues->get('4_range_of_age_type')"
    :disabled="$isLocked"
/>
<x-form-helper-text>原則、5歳階級での提供となります。</x-form-helper-text>
@endsection
