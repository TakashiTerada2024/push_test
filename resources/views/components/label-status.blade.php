{{-- ステータス用 --}}
<p {{ $attributes->merge(['class' => $classNames().' items-center p-2 text-white label-status']) }}>
    {{$statusName()}}
</p>
