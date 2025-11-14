<?php

use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Apply\Enterprise\Classification\QuestionItemName;
use Ncc01\Apply\Enterprise\Classification\QuestionSectionName;
$attachmentType = new AttachmentTypes();
$questionItemName = new QuestionItemName();
$questionSectionName = new QuestionSectionName();

return [
    //アップロード可能なファイルのサイズ(KB)
    'upload-max-filesize' => 20000,
    //smallIntegerの最大値
    'small-int-max' => 32767,
    //文字列入力の最大値
    'text-max' => 10000,
    'small-text-max' => 200,
    // 同時アップロードできる事務局送付資料ファイル数
    'max-number-of-files' => 10,
    /**
     * array 添付資料の種類名
     * @see AttachmentTypes::getDefiner()
     */
    'attachment-type' => $attachmentType->listOfName()->all(),
    //申請様式の保存ディレクトリ
    'apply-view-path-dir' => [
        1 => 'contents.apply.detail.linkage_17',
        2 => 'contents.apply.detail.totalling_17',
        3 => 'contents.apply.detail.linkage_21',
        4 => 'contents.apply.detail.totalling_21'
    ],
    //PDF
    'pdf-view-path-dir' => [
        1 => 'pdf.apply.linkage_17',
        2 => 'pdf.apply.totalling_17',
        3 => 'pdf.apply.linkage_21',
        4 => 'pdf.apply.totalling_21'
    ],

    'question-item-name' => $questionItemName->all(),
    'question-section-name' => $questionSectionName->all(),

    'system' => [
        'organization' => '国立がん研究センター',
        'name' => '全国がん登録情報利用申出システム',
        'title' => ' 全国がん登録情報利用申出',
    ],
];
