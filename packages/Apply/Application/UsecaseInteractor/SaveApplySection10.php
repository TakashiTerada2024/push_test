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

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection10ParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveApplySection10Interface;

/**
 * Class SaveApplySection09
 * @package Ncc01\Apply\Application\Usecase
 */
class SaveApplySection10 implements SaveApplySection10Interface
{
    /** @var ApplyRepositoryInterface $applyRepository */
    private $applyRepository;

    /**
     * SaveApplySection10 constructor.
     * @param ApplyRepositoryInterface $applyRepository
     */
    public function __construct(ApplyRepositoryInterface $applyRepository)
    {
        $this->applyRepository = $applyRepository;
    }

    /**
     * __invoke
     *
     * @param SaveApplySection10ParameterInterface $parameter
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveApplySection10ParameterInterface $parameter, int $applyId): void
    {
        $arrayParameter = [
            '10_remark' => $parameter->getRemark(),
            '10_clerk_name' => $parameter->getClerkName(),
            '10_clerk_contact_address' => $parameter->getClerkContactAddress(),
            '10_clerk_contact_email' => $parameter->getClerkContactEmail(),
            '10_clerk_contact_phone_number' => $parameter->getClerkContactPhoneNumber(),
            '10_clerk_contact_extension_phone_number' => $parameter->getClerkContactExtensionPhoneNumber(),
        ];
        $this->applyRepository->update($arrayParameter, $applyId);
    }
}
