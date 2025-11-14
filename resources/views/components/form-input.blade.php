{{-- BladeUiKitのinput要素に、JetStream側で指定しているものと同じクラスを適用 --}}
<x-buk-input
    id="{{$id}}"
    type="{{$type}}"
    name="{{$name}}"
    value="{{$value??''}}"
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm']) }}
/>
