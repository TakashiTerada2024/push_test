{{-- 最低限必要な情報登録画面 --}}
@inject('applyType','Ncc01\Apply\Enterprise\Classification\ApplyTypes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            がん情報 提供依頼申出作成
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- エラーメッセージ表示領域 --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">入力エラーがあります:</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- フラッシュメッセージ --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <x-buk-form name="form1" method="POST" action="{{ route('apply.minimum-info.save') }}">
                @csrf

                {{-- 隠しフィールド：申出種別ID --}}
                <input type="hidden" name="apply_type_id" value="{{ $skipData['apply_type_id'] ?? old('apply_type_id') }}">
                
                {{-- 隠しフィールド：スキップURL ID --}}
                <input type="hidden" name="skip_url_id" value="{{ $skipData['skip_url_id'] ?? old('skip_url_id') }}">

                <div class='md:grid md:grid-cols-1 md:gap-6 mb-4'>
                    <div class="bg-indigo-500 block mb-3 sm:rounded-md p-3" style="padding:10px;">
                        <span class="text-white">下記の項目にご記入ください</span>
                    </div>
                </div>

                <x-form-section>
                    <x-slot name="title">調査研究について</x-slot>
                    <x-slot name="description"></x-slot>
                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-label for="subject">研究課題名</x-form-label>
                            <x-form-error field="subject" />
                            <x-form-input id="subject" type="text" name="subject" class="w-full" :value="old('subject')" />

                            <x-form-label for="research_purpose">調査研究の目的</x-form-label>
                            <x-form-error field="research_purpose" />
                            <x-form-input-textarea
                                id="research_purpose"
                                name="research_purpose"
                                class="w-full"
                                rows="4"
                                placeholder="（例） 都道府県別の〇〇を年齢し、〜〜を統計する"
                            >{{ old('research_purpose') }}</x-form-input-textarea>

                            <x-form-label for="research_method">調査研究の方法</x-form-label>
                            <x-form-error field="research_method" />
                            <x-form-input-textarea
                                id="research_method"
                                name="research_method"
                                class="w-full"
                                rows="4"
                                placeholder="（例） 一般公開情報(e-statや院内がん登録全国集計等)と全国がん登録情報の地域別の情報を用いて対象年年齢調整罹患率を算出し、▲▲情報より得た△△を算出し、◯◯と年齢調整罹患率との相関を観察する。更に、がん種別、性別ごとに標準化の検定も行う。"
                            >{{ old('research_method') }}</x-form-input-textarea>

                            <x-form-label for="need_to_use">全国がん登録情報の利用の必要性</x-form-label>
                            <x-form-error field="need_to_use" />
                            <x-form-input-textarea
                                id="need_to_use"
                                name="need_to_use"
                                class="w-full"
                                rows="4"
                                placeholder="（例）当研究の目的である〇〇に関しては、一般公開情報やその他のデータソースから収集した情報からでは、□□が不足しており、○○の算出ができない。△△の情報を含む全国がん登録情報の利用が必要である。"
                            >{{ old('need_to_use') }}</x-form-input-textarea>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>

                <div class='md:grid md:grid-cols-1 md:gap-6 mb-4 mt-6'>
                    <div class="bg-indigo-500 block mb-3 sm:rounded-md p-3" style="padding:10px;">
                        <span class="text-white">ご連絡先をご記入ください</span>
                    </div>
                </div>

                <x-form-section>
                    <x-slot name="title">連絡先等</x-slot>
                    <x-slot name="description"></x-slot>
                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-label for="contact_name">名前</x-form-label>
                            <x-form-error field="contact_name" />
                            <x-form-input
                                id="contact_name"
                                type="text"
                                name="contact_name"
                                class="w-full"
                                :value="old('contact_name')"
                            />

                            <x-form-label for="contact_name_kana">カナ</x-form-label>
                            <x-form-error field="contact_name_kana" />
                            <x-form-input
                                id="contact_name_kana"
                                type="text"
                                name="contact_name_kana"
                                class="w-full"
                                :value="old('contact_name_kana')"
                            />

                            <x-form-label for="contact_affiliation">所属</x-form-label>
                            <x-form-error field="contact_affiliation" />
                            <x-form-input
                                id="contact_affiliation"
                                type="text"
                                name="contact_affiliation"
                                class="w-full"
                                :value="old('contact_affiliation')"
                            />

                            <x-form-label for="contact_phone">電話番号</x-form-label>
                            <x-form-error field="contact_phone" />
                            <x-form-input
                                id="contact_phone"
                                type="text"
                                name="contact_phone"
                                class="w-full"
                                :value="old('contact_phone')"
                            />

                            <x-form-label for="contact_extension">内線</x-form-label>
                            <x-form-error field="contact_extension" />
                            <x-form-input
                                id="contact_extension"
                                type="text"
                                name="contact_extension"
                                class="w-full"
                                :value="old('contact_extension')"
                            />
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>
                
                {{-- ボタン --}}
                <x-action-area>
                    <x-button-primary type="submit" name="action" value="submit">
                        申請情報送信
                    </x-button-primary>
                </x-action-area>
            </x-buk-form>
        </div>
    </div>
</x-app-layout> 