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

namespace Ncc01\User\Application\Service;

use Ncc01\User\Application\GatewayInterface\AuthInterface;

/**
 * ValidatePermission
 *
 * @package Ncc01\User\Application\Service
 */
class ValidatePermission
{
    /** @var AuthInterface $auth */
    private $auth;

    /**
     * ValidatePermission constructor.
     * @param AuthInterface $auth
     */
    public function __construct(
        AuthInterface $auth
    ) {
        $this->auth = $auth;
    }

    /**
     * __invoke
     *
     * @param ValidatePermissionParameterInterface $parameter
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(ValidatePermissionParameterInterface $parameter): bool
    {
        //認証済ユーザーの取得
        $authenticatedUser = $this->auth->getAuthenticatedUser();
        //パーミッション仕様オブジェクトの取得
        $spec = $parameter->getPermissionSpec($authenticatedUser);
        //パーミッションの判定
        return $spec->isSatisfied();
    }
}
