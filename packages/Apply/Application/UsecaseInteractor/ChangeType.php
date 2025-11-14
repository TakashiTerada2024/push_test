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
use Ncc01\Apply\Application\InputBoundary\ChangeTypeParameterInterface;
use Ncc01\Apply\Application\Usecase\ChangeTypeInterface;

/**
 * ChangeType
 *
 * @package Ncc01\Apply\Application\UsecaseInteractor
 */
class ChangeType implements ChangeTypeInterface
{
    /** @var ApplyRepositoryInterface $applyRepository */
    private $applyRepository;

    /**
     * ChangeType constructor.
     * @param ApplyRepositoryInterface $applyRepository
     */
    public function __construct(ApplyRepositoryInterface $applyRepository)
    {
        $this->applyRepository = $applyRepository;
    }

    /**
     * __invoke
     *
     * @param ChangeTypeParameterInterface $parameter
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(ChangeTypeParameterInterface $parameter, int $applyId): void
    {
        //$parameterを使って更新する処理を実装
        $parameter = [
            'type_id' => $parameter->getApplyTypeId()
        ];
        $this->applyRepository->update($parameter, $applyId);
    }
}
