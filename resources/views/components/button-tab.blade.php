{{-- タブ切り替え風のボタン --}}
<button {{ $attributes->merge(['type' => 'button', 'class' => 'button-tab inline-flex items-center bg-white font-semibold text-md text-gray-700 uppercase tracking-widest focus:outline-none focus:border-blue-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>