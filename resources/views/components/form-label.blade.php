{{-- Label JetStream / UiKit を合体 --}}
@props(['for', 'mark' => ''])
<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if($mark == 'require')<span class="text-red-700 text-xs">[必須]</span>
    @elseif($mark == 'applicable')<span class="text-blue-800 text-xs">[該当時]</span>
    @endif
</label>

