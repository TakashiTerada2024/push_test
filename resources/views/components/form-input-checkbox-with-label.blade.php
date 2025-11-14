{{-- チェックボックス用ブロック --}}
<x-buk-checkbox {{$attributes->merge([])}} id="{{$id}}" name="{{$name}}" value="{{$value}}" :checked="(is_array($checkedValue)?in_array($value,$checkedValue):($value === $checkedValue))"/>
<label for="{{$id}}">{{$slot}}</label>

