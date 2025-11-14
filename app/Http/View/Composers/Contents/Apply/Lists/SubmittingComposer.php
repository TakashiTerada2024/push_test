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

namespace App\Http\View\Composers\Contents\Apply\Lists;

use App\QueryParameter\ApplyListParameter;
use GitBalocco\LaravelUiViewComposer\BasicComposer;
use GitBalocco\LaravelUiViewComposer\Contract\ViewComposerInterface;
use Ncc01\Apply\Application\QueryInterface\ApplyListInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

/**
 * PriorConsultationComposer
 *
 * @package App\Http\View\Composers\Contents\Apply\Lists
 */
class SubmittingComposer extends BasicComposer implements ViewComposerInterface
{
    /** @var ApplyListInterface $applyListQuery */
    private $applyListQuery;

    public function __construct(ApplyListInterface $applyListQuery)
    {
        $this->applyListQuery = $applyListQuery;
    }


    /**
     * createParameter
     * テンプレートファイルにアサインする変数を定義
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createParameter(): array
    {
        //パラメタ1
        $parameter1 = new ApplyListParameter();
        $parameter1->setStatus([ApplyStatuses::SUBMITTING_DOCUMENT]);
        $parameter1->setIsRepliedBySecretariat(false);
        $parameter1->setIsShowAccepted(false);
        //パラメタ2
        $parameter2 = new ApplyListParameter();
        $parameter2->setStatus([ApplyStatuses::SUBMITTING_DOCUMENT]);
        $parameter2->setIsRepliedBySecretariat(true);
        $parameter2->setIsShowAccepted(false);

        return [
            'appliesNotReplied' => $this->applyListQuery->__invoke($parameter1),
            'appliesReplied' => $this->applyListQuery->__invoke($parameter2)
        ];
    }
}
