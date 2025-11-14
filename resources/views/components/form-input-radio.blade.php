{{-- BladeUiKitのinput要素に、JetStream側で指定しているものと同じクラスを適用 --}}
<input id="{{$id}}" type="radio" name="{{$name}}" value="{{$value??''}}" {{ $attributes }} {{$checked}} />
<label for="{{$id}}">{{$slot}}</label>
