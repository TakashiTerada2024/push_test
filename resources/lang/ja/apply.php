<?php


return [
    'format' => [
        '2-1' => [
            'helper-text' => [
                '2' => [
                    'ethical-review' => '学術的公表の予定がある場合は、当該研究について、所属機関の倫理審査委員会の承認、審査対象外の場合は、倫理審査委員会の対象外(審査不要)であることの証明を申出時に添えてください。',
                ]
            ]
        ],
        'notice-upload-max-filesize' => [
            'notice' => '複数のファイルをZIPファイルにまとめて提出することは控えてください。',
            'size' => floor(config('app-ncc01.upload-max-filesize') / 1000) . 'MBまで',
        ],
        'notice-upload-multiple-file' => [
            'max-number' => '10ファイルまで同時アップロード可能(shift、ctrlキーで複数選択できます)'
        ],
        'notice-submit-file' => [
            'notice' => '複数のファイルを添付される場合は1つずつアップロードして「添付ファイル」画面に移動して提出するファイルを選択してください。なお、同画面からもアップロードすることができますが、アップロード後にファイルの種別（誓約書/委託契約書/研究計画書/集計案
            等）を選択する必要があります。',
        ],
        //PDF、および申請概要画面から参照される「様式名」
        'name' => [
            'linkage_17' => '様式第2_1申出17条',
            'linkage_21' => '様式第2-1号申出21_3',
            'totaling_17' => '様式第2_1申出17条',
            'totaling_21' => '様式第2-1号申出21_4'
        ],
        //PDF、および申請概要画面から参照される「宛先」
        'destination'=>[
            'linkage_17' => '厚生労働大臣 殿',
            'linkage_21' => '厚生労働大臣 殿',
            'totaling_17' => '国立研究開発法人<br />国立がん研究センター 理事長 殿',
            'totaling_21' => '国立研究開発法人<br />国立がん研究センター 理事長 殿',
        ],
    ],
    'url' => [
        //行政関係者用（法令17条）の申出様式についてまとめられているがん研究センター内のURL
        'format-17' => 'https://ganjoho.jp/med_pro/cancer_control/can_reg/national/datause/lg.html#moshidebunsho',
        //研究者等用（法令21条）の申出様式についてまとめられているがん研究センター内のURL
        'format-21' => 'https://ganjoho.jp/med_pro/cancer_control/can_reg/national/datause/researcher.html#moshidebunsho',
        //申出マニュアル
        'manual' => 'https://ganjoho.jp/med_pro/cancer_control/can_reg/national/datause/general.html#anchor4',
    ],
    'message' => [
        'deleted' => '---削除---',
        'not_found' => '申出が見つかりません',
        'change_request_success' => '変更申出作成が完了しました。',
        'change_request_failed' => '変更申出作成が失敗しました'
    ]

];
