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

use App\Models\Apply;
use Illuminate\Database\Migrations\Migration;
use Ncc01\Messaging\Application\Usecase\SendMessageToSecretariatInterface;
use Ncc01\Messaging\Application\InputData\SendMessageToSecretariatParameter;
use Illuminate\Database\Schema\Blueprint;

class InsertAppliesForTesting3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //本番環境では以下のデータは不要
        if (config('app.env') === 'production') {
            return;
        }

//        $model = Apply::findOrNew(9);
//        $model->id = 9;
//        $model->user_id = 101;
//        $model->type_id = 1;
//        $model->affiliation = '所属9';
//        $model->status = 3; //申出文書確認中
//        $model->subject = 'テストデータ9/申出文書確認中（行政関係者・リンケージ利用）研究タイトル';
//        $model->{'10_applicant_name'} = '9/テスト用申請者アカウントA';
//        $model->save();
//
//        /** @var Apply $model */
//        $model = Apply::findOrNew(10);
//        $model->id = 10;
//        $model->user_id = 101;
//        $model->type_id = 2;
//        $model->affiliation = '所属10';
//        $model->status = 3; //申出文書確認中
//        $model->subject = 'テストデータ10/申出文書確認中（行政関係者・集計統計利用）研究タイトル';
//        $model->{'10_applicant_name'} = '10/テスト用申請者アカウントA';
//        $model->save();
//
//        /** @var Apply $model */
//        $model = Apply::findOrNew(11);
//        $model->id = 11;
//        $model->user_id = 101;
//        $model->type_id = 3;
//        $model->affiliation = '所属11';
//        $model->status = 3; //申出文書確認中
//        $model->subject = 'テストデータ11/事前相談中（研究者等・リンケージ利用）研究タイトル';
//        $model->{'10_applicant_name'} = '11/テスト用申請者アカウントA';
//        $model->save();
//
//        /** @var Apply $model */
//        $model = Apply::findOrNew(12);
//        $model->id = 12;
//        $model->user_id = 101;
//        $model->type_id = 4;
//        $model->affiliation = '所属12';
//        $model->status = 3; //申出文書確認中
//        $model->subject = 'テストデータ12/事前相談中（研究者等・集計統計利用）研究タイトル';
//        $model->{'10_applicant_name'} = '12/テスト用申請者アカウントA';
//        $model->save();
//
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

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign('notifications_apply_id_foreign');
            $table->foreign('apply_id')
                ->references('id')
                ->on('applies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //本番環境では以下のデータは不要
        if (config('app.env') === 'production') {
            return;
        }

//        Apply::findOrNew(9)->delete();
//        Apply::findOrNew(10)->delete();
//        Apply::findOrNew(11)->delete();
//        Apply::findOrNew(12)->delete();
    }

    private function parameters(): iterable
    {
        return [
            [9, 'メッセージ本文/9', 101, '9/申請者A'],
            [10, 'メッセージ本文/10', 101, '10/申請者A'],
            [11, 'メッセージ本文/11', 101, '11/申請者A'],
            [12, 'メッセージ本文/12', 101, '12/申請者A'],
        ];
    }
}
