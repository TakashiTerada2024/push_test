@if(!is_null($currentFile))
    <a href="{{route('attachment.download',['id'=>$currentFile['id']])}}">添付ID:{{$currentFile['id']}} {{$currentFile['name']}}</a>
@else
    (未指定)
@endif

<x-buk-input
    id="{{$id}}"
    type="file"
    name="{{$name}}"
    value="{{$value??''}}"
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md']) }}
/>
