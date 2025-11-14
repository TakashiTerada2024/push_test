<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ロック制御管理
        </h2>
    </x-slot>

    @inject('attachmentTypes', 'Ncc01\Apply\Enterprise\Classification\AttachmentTypes')
    @inject('screenLocks', 'Ncc01\Apply\Enterprise\Classification\ScreenLocks')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium mb-4">1. 画面単位の編集ロック</h3>
                                @if ($errors->any())
                                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('lock.management.save', ['applyId' => $applyId]) }}">
                                    @csrf
                                    <div class="flex flex-col">
                                        <div class="flex justify-between items-center px-4 py-2 border-b-2 border-gray-200 font-medium text-gray-600">
                                            <div>画面名</div>
                                            <div>チェック付きは編集不可</div>
                                        </div>
                                        @foreach($screenLocks->all() as $value => $label)
                                            <div class="flex justify-between items-center px-4 py-2 border-b border-gray-100">
                                                <label for="screen_lock_{{ $value }}">{{ $label }}</label>
                                                <input type="checkbox"
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    id="screen_lock_{{ $value }}"
                                                    name="screen_locks[{{ $value }}]"
                                                    value="true"
                                                    {{ $formValues->get('screen_locks')[$value]??false?"checked":""}}
                                                    >
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-6 flex justify-center">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                            画面ロック状態を更新
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium mb-4">2. 添付資料種別ごとの編集ロック</h3>
                                <form method="POST" action="{{ route('lock.management.attachment.save', ['applyId' => $applyId]) }}">
                                    @csrf
                                    <div class="flex flex-col">
                                        <div class="flex justify-between items-center px-4 py-2 border-b-2 border-gray-200 font-medium text-gray-600">
                                            <div>添付資料種別</div>
                                            <div>チェック付きは編集不可</div>
                                        </div>
                                        @foreach($attachmentTypes->listOfNameForApplicant() as $value => $label)
                                            <div class="flex justify-between items-center px-4 py-2 border-b border-gray-100">
                                                <label for="attachment_lock_{{ $value }}">{{ $label }}</label>
                                                <input type="checkbox"
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    id="attachment_lock_{{ $value }}"
                                                    name="attachment_locks[{{ $value }}]"
                                                    value="true"
                                                    {{ $formValues->get('attachment_locks')[$value]??false?"checked":""}}>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-6 flex justify-center">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                            添付資料ロック状態を更新
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
