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

namespace Tests\Feature\Http\Controllers\Apply;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\Http\Controllers\PostHttpStatusCodeTestBase;

/**
 * 窓口組織ロールでのログイン時、申請種別を変更できることのテスト
 * Class SecretariatCanChangeApplyTypeTest
 * @package Tests\Feature\Http\Controllers\Apply
 */
class SecretariatCanChangeApplyTypeTest extends PostHttpStatusCodeTestBase
{
    /**
     * testHttpStatusAsActor
     *
     * @param string $url
     * @param int $expectedStatusCode
     * @param array $postParameters
     * @param string|null $redirectTo
     * @dataProvider targetUrlsAndExpectedStatusCodeProvider
     * @covers       \App\Http\Controllers\Apply\ChangeTypeController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function testHttpStatusAsActor(
        string $url,
        int $expectedStatusCode,
        array $postParameters,
        ?string $redirectTo = null
    ) {
        parent::testHttpStatusAsActor(
            $url,
            $expectedStatusCode,
            $postParameters,
            $redirectTo
        );
    }


    /**
     * targetUrlsAndExpectedStatusCodeProvider
     *
     * @return array[]
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function targetUrlsAndExpectedStatusCodeProvider(): array
    {
        return [
            ['/apply/change_type/1', 302, ['type_id' => 1], '/welcome'],
            ['/apply/change_type/2', 302, ['type_id' => 2], '/welcome'],
            ['/apply/change_type/3', 302, ['type_id' => 3], '/welcome'],
            ['/apply/change_type/4', 302, ['type_id' => 4], '/welcome'],
        ];
    }

    /**
     * getActor
     *
     * @return User
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function getActor(): User
    {
        //窓口組織ロールのアカウント
        return User::find(102);
    }
}
