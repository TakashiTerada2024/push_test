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

namespace App\Gateway;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use LogicException;
use Ncc01\User\Application\Exception\NoAuthenticatedUserException;
use Ncc01\User\Application\GatewayInterface\AuthInterface;
use Ncc01\User\Enterprise\User;

/**
 * Auth
 *
 * @package App\Common
 */
class Auth implements AuthInterface
{
    /** @var Guard $guard */
    private $guard;

    /**
     * Auth constructor.
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->guard = $factory->guard();
    }


    /**
     * getAuthenticatedUser
     *
     * @return User
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) Laravelファサードに依存していても問題ない。
     */
    public function getAuthenticatedUser(): User
    {
        if (!Session::isStarted()) {
            throw new NoAuthenticatedUserException(
                '(Note: If you execute __invoke () in the constructor, Session may not be started.)'
            );
        }
        /** @var Authenticatable $user */
        $user = $this->guard->user();

        if (is_null($user)) {
            throw new NoAuthenticatedUserException();
        }
        if (!is_a($user, \App\Models\User::class)) {
            throw new LogicException('authenticated user type must be "App\Models\User".');
        }
        /** @var \App\Models\User $user */
        return new User($user->id, $user->name, $user->role_id);
    }
}
