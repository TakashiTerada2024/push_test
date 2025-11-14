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

namespace Ncc01\Pdf\Application\UsecaseInteractor;

use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Pdf\Application\Usecase\ValidateCanDisplayPdfOfApplyInterface;
use Ncc01\User\Application\GatewayInterface\AuthInterface;

/**
 * ValidateCanDisplayPdfOfApply
 *
 * @package Ncc01\Pdf\Application\UsecaseInteractor
 */
class ValidateCanDisplayPdfOfApply implements ValidateCanDisplayPdfOfApplyInterface
{
    public function __construct(private AuthInterface $auth)
    {
    }

    public function __invoke(int $applyStatusId, ?int $applyTypeId): bool
    {
        if (is_null($applyTypeId)) {
            return false;
        }

        //ログイン者が事務局権限の場合
        if ($this->auth->getAuthenticatedUser()->getRole()->isSecretariat()) {
            return $this->secretariatCheck($applyStatusId);
        }

        if ($this->auth->getAuthenticatedUser()->getRole()->isApplicant()) {
            return $this->applicantCheck($applyStatusId);
        }
        return false;
    }

    private function secretariatCheck($applyStatusId): bool
    {
        if (
            in_array($applyStatusId, [
            ApplyStatuses::SUBMITTING_DOCUMENT,
            ApplyStatuses::CHECKING_DOCUMENT,
            ApplyStatuses::ACCEPTED,
            ])
        ) {
            return true;
        }
        return false;
    }

    private function applicantCheck($applyStatusId): bool
    {
        if (
            in_array($applyStatusId, [
                    ApplyStatuses::SUBMITTING_DOCUMENT,
                    ApplyStatuses::CHECKING_DOCUMENT,
                    ApplyStatuses::CREATING_DOCUMENT])
        ) {
            return true;
        }
        return false;
    }
}
