{{-- 提供可否相談 --}}
@inject('applyType','Ncc01\Apply\Enterprise\Classification\ApplyTypes')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            がん情報 提供依頼可否相談
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <x-buk-form name="form1" method="POST" action="">

                <div class='md:grid md:grid-cols-1 md:gap-6 mb-4'>
                    <div class="bg-indigo-500 block mb-3 sm:rounded-md p-3" style="padding:10px;">
                        <span class="text-white">お問い合わせの種類を選択してください</span>
                    </div>
                </div>


                <x-form-section>
                    <x-slot name="title">申出者、全国がん登録の利用種別</x-slot>
                    <x-slot name="description"></x-slot>
                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-error field="type_id" />
                            <x-form-input-radios id="type_id" name="type_id" :options="$applyType->listOfName()" :checked-value="$formValues->get('type_id')" ><br /></x-form-input-radios>
                            <x-form-input-radio id="type_id_undefined" name="type_id" value="99" :checked="$formValues->get('type_id')==='99' || is_null($formValues->get('type_id'))">わからない</x-form-input-radio>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>

                <div class='md:grid md:grid-cols-1 md:gap-6 mb-4 mt-6'>
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
                            <x-form-input id="subject" type="text" name="subject" class="w-full" :value="$formValues->get('subject')" />

                            <x-form-label for="">研究期間（始期）</x-form-label>
                            <x-form-error field="6_research_period_start" />
                            <x-form-input
                                id="6_research_period_start"
                                type="text"
                                name="6_research_period_start"
                                class="w-full"
                                placeholder="YYYY-MM-DD"
                                pattern="(?:19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])"
                                title="有効な日付を YYYY-MM-DD 形式で入力してください（例：2024-03-15）"
                                :value="$formValues->get('6_research_period_start')"
                            />

                            <x-form-label for="6_research_period_end">研究期間（終期）</x-form-label>
                            <x-form-error field="6_research_period_end" />
                            <x-form-input
                                id="6_research_period_end"
                                type="text"
                                name="6_research_period_end"
                                class="w-full"
                                placeholder="YYYY-MM-DD"
                                pattern="(?:19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])"
                                title="有効な日付を YYYY-MM-DD 形式で入力してください（例：2024-03-15）"
                                :value="$formValues->get('6_research_period_end')"
                            />

                            <x-form-label for="2_purpose_of_use">調査研究の目的</x-form-label>
                            <x-form-error field="2_purpose_of_use" />
                            <x-form-input-textarea
                                id="2_purpose_of_use"
                                name="2_purpose_of_use"
                                class="w-full" rows="4"
                                placeholder="（例）都道府県別の◯◯を集計し、**を検討する"
                            >{{$formValues->get('2_purpose_of_use')}}</x-form-input-textarea>

                            <x-form-label for="5_research_method">調査研究の方法</x-form-label>
                            <x-form-error field="5_research_method" />
                            <x-form-input-textarea
                                id="5_research_method"
                                name="5_research_method"
                                class="w-full"
                                rows="4"
                                placeholder="（例）一般公開情報[e-statや院内がん登録全国集計等]と全国がん登録情報の地域別の情報を用いて対象都市の年齢調整罹患率を　算出し、▲▲情報より得た、地域別の▽▽を算出し、▽▽と年齢調整罹患率との相関を観察する。更に、がん種別、性別ごとに層別化の検証も行う。"
                            >{{$formValues->get('5_research_method')}}</x-form-input-textarea>

                            <x-form-label for="2_need_to_use">全国がん登録情報の利用の必要性</x-form-label>
                            <x-form-error field="2_need_to_use" />
                            <x-form-input-textarea
                                id="2_need_to_use"
                                name="2_need_to_use"
                                class="w-full"
                                rows="4"
                                placeholder="（例）当研究の目的である〇〇に関しては、一般公開情報やその他のデータソースから収集した情報からでは、□□が不足しており、○○の算出ができない。△△の情報を含む全国がん登録情報の利用が必要である。"
                            >{{$formValues->get('2_need_to_use')}}</x-form-input-textarea>
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>

                <x-form-section>
                    <x-slot name="title">その他、全国がん登録情報の利用に関する申出手続き等のご質問</x-slot>
                    <x-slot name="description"></x-slot>
                    <x-slot name="form">
                        <div class="col-span-6">
                            <x-form-error field="question_at_prior_consultation" />
                            <x-form-input-textarea
                                id="question_at_prior_consultation"
                                name="question_at_prior_consultation"
                                class="w-full"
                                rows="4"
                                placeholder=""
                            >{{$formValues->get('question_at_prior_consultation')}}</x-form-input-textarea>
                        </div>
                    </x-slot>
                </x-form-section>

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
                            <x-form-label for="10_applicant_name">名前</x-form-label>
                            <x-form-error field="10_applicant_name" />
                            <x-form-input
                                id="10_applicant_name"
                                type="text"
                                name="10_applicant_name"
                                class="w-full"
                                :value="$formValues->get('10_applicant_name')"
                            />

                            <x-form-label for="10_applicant_name_kana">カナ</x-form-label>
                            <x-form-error field="10_applicant_name_kana" />
                            <x-form-input
                                id="10_applicant_name_kana"
                                type="text"
                                name="10_applicant_name_kana"
                                class="w-full"
                                :value="$formValues->get('10_applicant_name_kana')"
                            />

                            <x-form-label for="">所属</x-form-label>
                            <x-form-error field="affiliation" />
                            <x-form-input
                                id="affiliation"
                                type="text"
                                name="affiliation"
                                class="w-full"
                                :value="$formValues->get('affiliation')"
                            />

                            <x-form-label for="10_applicant_phone_number">電話番号</x-form-label>
                            <x-form-error field="10_applicant_phone_number" />
                            <x-form-input
                                id="10_applicant_phone_number"
                                type="text"
                                name="10_applicant_phone_number"
                                class="w-full"
                                :value="$formValues->get('10_applicant_phone_number')"
                            />

                            <x-form-label for="10_applicant_extension_phone_number">内線</x-form-label>
                            <x-form-error field="10_applicant_extension_phone_number" />
                            <x-form-input
                                id="10_applicant_extension_phone_number"
                                type="text"
                                name="10_applicant_extension_phone_number"
                                class="w-full"
                                :value="$formValues->get('10_applicant_extension_phone_number')"
                            />
                        </div>
                    </x-slot>
                </x-form-section>

                <x-jet-section-border/>
                {{-- ボタン --}}
                <x-action-area>

                    <a href="{{route('apply.lists.my_list')}}"><x-button-secondary class="mr-2" type="button">戻る</x-button-secondary></a>
                    <x-button-secondary class="mr-2" type="button" onclick="javascript:tmpSaveSubmit();">一時保存</x-button-secondary>
                    <x-jet-button>事務局へ送信</x-jet-button>
                </x-action-area>

            </x-buk-form>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            function tmpSaveSubmit() {
                const form = document.form1;
                const originalAction = form.action;
                form.action = '{{route('apply.tmp_save',['applyId'=>$applyId])}}';

                // フォームの各入力フィールドのバリデーションを実行
                const isValid = form.checkValidity();
                if (!isValid) {
                    form.reportValidity();
                    form.action = originalAction;
                    return;
                }

                form.submit();
                form.action = originalAction;
            }
        </script>
    @endpush

</x-app-layout>

