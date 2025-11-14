{{-- 申出様式 --}}
@inject('applyStatuses','Ncc01\Apply\Enterprise\Classification\ApplyStatuses')

<x-app-layout>
    <x-slot name="header">
        <div class="block mb-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ config('app-ncc01.system.title') }}(申出番号:{{$id}}) 概要
            </h2>

        </div>
    </x-slot>

    <div>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- progress bar --}}
            {{-- 終わったものはover、現在のステータスはnowとなるようにclassの出し分けってできますか…？？ --}}
            <ul class="progress-bar bg-white shadow rounded-md mb-2">
                <li class="@if($applyBaseInfo->getStatusId()>1) over @elseif($applyBaseInfo->getStatusId()===1) now @endif">{{$applyStatuses->value(1)}}</li>
                <li class="@if($applyBaseInfo->getStatusId()>2) over @elseif($applyBaseInfo->getStatusId()===2) now @endif">{{$applyStatuses->value(2)}}</li>
                <li class="@if($applyBaseInfo->getStatusId()>3) over @elseif($applyBaseInfo->getStatusId()===3) now @endif">{{$applyStatuses->value(3)}}</li>
                <li class="@if($applyBaseInfo->getStatusId()>4) over @elseif($applyBaseInfo->getStatusId()===4) now @endif">{{$applyStatuses->value(4)}}</li>
                <li class="@if($applyBaseInfo->getStatusId()>5) over @elseif($applyBaseInfo->getStatusId()===5) now @endif">{{$applyStatuses->value(5)}}</li>
            </ul>

            {{-- 0. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>申出基本情報</span>
                        @if($screenLocks['basic']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">

                        <div class="block w-full">
                            <div >
                                <x-label-status class="text-center mb-4" :status-id="$applyBaseInfo->getStatusId()" />
                            </div>

                        </div>


                        <div class="block w-full">
                            <x-form-label for="">申出件名</x-form-label>
                            <span id="apply-title">@yield('apply-title','申出件名（各様式ごとに定められた件名）')</span>
                        </div>


                        <div class="block w-full mt-2">
                            <x-form-label for="">申出概要</x-form-label>
                            <span id="apply-summary">@yield('apply-summary','申出概要（各様式ごとに定められた概要）')</span>
                        </div>

                        <div class="block w-full mt-2">
                            <x-form-label for="">申出様式</x-form-label>
                            <span id="apply-format-name">@yield('apply-format-name','様式の名称') </span>
                        </div>

                        <div class="block w-full mt-2">
                            <x-form-label for="">申出先</x-form-label>
                            <span id="apply-destination">@yield('apply-destination','申出先の組織、役職名等') </span>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['basic']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.basic.info',['applyId' => $id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>

            <x-jet-section-border/>


            {{-- 1. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.1') }}</span>
                        @if($screenLocks['section1']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <div class="p-2">
                            @yield('information-type','申出する情報の種類')
                        </div>
                        <x-apply-validation-info :validation-result="$validationResult->get('section01')" :hasAttachment=true/>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section1']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section1',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>

            <x-jet-section-border/>

            {{-- 2. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.2') }}</span>
                        @if($screenLocks['section2']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section02')" :hasAttachment=true/>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section2']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section2',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>

            <x-jet-section-border/>

            {{-- 3. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.3') }}</span>
                        @if($screenLocks['section3']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section03')" :hasAttachment=true/>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section3']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section3',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>

            <x-jet-section-border/>
            {{-- 4. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.4') }}</span>
                        @if($screenLocks['section4']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section04')" />
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section4']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section4',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>

            <x-jet-section-border/>

            {{-- 5. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.5') }}</span>
                        @if($screenLocks['section5']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section05')" :hasAttachment=true/>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section5']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section5',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>

            <x-jet-section-border/>
            {{-- 6. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.6') }}</span>
                        @if($screenLocks['section6']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section06')" />
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section6']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section6',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>

            <x-jet-section-border/>
            {{-- 7. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.7') }}</span>
                        @if($screenLocks['section7']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section07')" :hasAttachment=true/>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section7']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section7',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>
            <x-jet-section-border/>

            {{-- 8. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.8') }}</span>
                        @if($screenLocks['section8']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section08')" />
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section8']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section8',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>
            <x-jet-section-border/>

            {{-- 9. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.9') }}</span>
                        @if($screenLocks['section9']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section09')" />
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section9']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section9',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>
            <x-jet-section-border/>

            {{-- 10. --}}
            <x-jet-form-section submit="">
                <x-slot name="title">
                    <div class="flex items-center">
                        <span>{{ config('app-ncc01.question-section-name.10') }}</span>
                        @if($screenLocks['section10']??false)
                            <x-icon-lock class="h-5 w-5 text-gray-500 ml-2" />
                        @endif
                    </div>
                </x-slot>
                <x-slot name="description"></x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <x-apply-validation-info :validation-result="$validationResult->get('section10')" />
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <x-lock-message :show="$screenLocks['section10']??false" />
                        </div>
                        <div>
                            <a href="{{route('apply.detail.section10',['applyId'=>$id])}}"><x-jet-button type="button">詳細</x-jet-button></a>
                        </div>
                    </div>
                </x-slot>
            </x-jet-form-section>
            <x-jet-section-border/>

            {{-- action --}}
            <x-action-area>
                <x-buk-form method="post" action="{{route('apply.start_checking_document',['applyId'=>$id])}}">
                    @if($canDisplayCheckingButton && $applyBaseInfo->getStatusId()===2)
                        @if($canStartChecking)
                            <x-jet-danger-button type="submit">申出文書 承認依頼</x-jet-danger-button>
                        @else
                            <div>
                                <ul>
                                    <x-jet-danger-button type="button" disabled>申出文書 承認依頼</x-jet-danger-button>
                                    <li class="font-bold text-accent-500">※必須項目の入力が不足している、または添付ファイルが提出されていません</li>
                                    <li class="font-bold text-accent-500">
                                        ※窓口組織へ提出するファイルを<a class="text-main-500" href="{{ route('attachment.apply.show',['applyId'=>Request::route('applyId')]) }}">「添付ファイル画面」</a>へ移動して選択してください
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @else
                        <x-jet-danger-button type="button" disabled>申出文書 承認依頼</x-jet-danger-button>
                    @endif
                </x-buk-form>
            </x-action-area>

        </div>
    </div>

</x-app-layout>
