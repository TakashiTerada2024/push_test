@foreach($options as $keyOfList => $valueOfList)
    <x-form-input-radio
        id="{{$id}}_{{$keyOfList}}"
        name="{{$name}}"
        value="{{$keyOfList}}"
        checked-value="{{$checkedValue}}"
        :disabled="$disabled ?? false"
    >{{$valueOfList}}</x-form-input-radio>{{$slot}}
@endforeach

