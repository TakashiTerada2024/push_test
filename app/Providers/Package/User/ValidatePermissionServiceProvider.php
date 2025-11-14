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

namespace App\Providers\Package\User;

use Illuminate\Support\ServiceProvider;

/**
 * ValidatePermissionServiceProvider
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @package App\Providers\Package\User
 */
class ValidatePermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionModifyApply::class
        );
        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionShowApply::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionModifyAttachmentTypeInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionModifyAttachmentType::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionShowApplyListsInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionShowApplyLists::class
        );
        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyTypeInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionChangeApplyType::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyStatusInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionChangeApplyStatus::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionStartApplyInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionStartApply::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionDeleteAttachmentInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionDeleteAttachment::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionModifyAttachmentInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionModifyAttachment::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionShowAttachmentBySecretariatApplyInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionShowAttachmentBySecretariat::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionAddAttachmentBySecretariatApplyInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionAddAttachmentBySecretariat::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyMemoInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionModifyApplyMemo::class
        );

        $this->app->bind(
            \Ncc01\User\Application\Usecase\ValidatePermissionSubmitAttachmentInterface::class,
            \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionSubmitAttachment::class
        );
    }
}
