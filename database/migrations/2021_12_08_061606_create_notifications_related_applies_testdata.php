<?php
/**
 * Balocco Inc.
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 株式会社バロッコはシステム設計・開発会社として、
 * 社員・顧客企業・パートナー企業と共に企業価値向上に全力を尽くします
 *
 * 1. プロフェッショナル集団として人間力・経験・知識を培う
 * 2. システム設計・開発を通じて、顧客企業の成長を活性化する
 * 3. 顧客企業・パートナー企業・弊社全てが社会的意義のある事業を営み、全てがwin-winとなるビジネスをする
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 本社所在地
 * 〒101-0032　東京都千代田区岩本町2-9-9 TSビル4F
 * TEL:03-6240-9877
 *
 * 大阪営業所
 * 〒530-0063　大阪府大阪市北区太融寺町2-17 太融寺ビル9F 902
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;
use Ncc01\Messaging\Application\InputData\SendMessageToSecretariatParameter;
use Ncc01\Messaging\Application\Usecase\SendMessageToSecretariatInterface;

class CreateNotificationsRelatedAppliesTestdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        /** @var SendMessageToSecretariatInterface $sendMessageToSecretariat */
//        $sendMessageToSecretariat = App::make(SendMessageToSecretariatInterface::class);
//        foreach ($this->parameters() as $array) {
//            /** @var SendMessageToSecretariatParameter $messageParameter */
//            $messageParameter = App::make(SendMessageToSecretariatParameter::class);
//            $messageParameter->setApplyId($array[0]);
//            $messageParameter->setMessageBody($array[1]);
//            $messageParameter->setSenderUserId($array[2]);
//            $messageParameter->setSenderUserName($array[3]);
//            $sendMessageToSecretariat->__invoke($messageParameter);
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        \Illuminate\Support\Facades\DB::statement('DELETE from notifications WHERE apply_id in(1,2,3,4,5,6,7,8)');
    }

    private function parameters(): iterable
    {
        return [
            [1, 'メッセージ本文/1', 101, '1/申請者A'],
            [2, 'メッセージ本文/2', 101, '2/申請者A'],
            [3, 'メッセージ本文/3', 101, '3/申請者A'],
            [4, 'メッセージ本文/4', 101, '4/申請者A'],
            [5, 'メッセージ本文/5', 101, '5/申請者A'],
            [6, 'メッセージ本文/6', 101, '6/申請者A'],
            [7, 'メッセージ本文/7', 101, '7/申請者A'],
            [8, 'メッセージ本文/8', 101, '8/申請者A'],
        ];
    }
}
