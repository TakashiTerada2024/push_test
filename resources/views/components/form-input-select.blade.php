<select id="{{$id}}" name="{{$name}}" {{$attributes->merge([])}}>
    @if($options??false)
        <option value=""></option>
        @foreach($options as $keyOfList => $valueOfList)
            <option value="{{$keyOfList}}" {{$selected($keyOfList)}}>{{$valueOfList}}</option>
        @endforeach
    @else
        {{$slot}}
    @endif
</select>
