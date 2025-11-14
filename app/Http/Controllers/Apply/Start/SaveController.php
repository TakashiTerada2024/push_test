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

namespace App\Http\Controllers\Apply\Start;

use App\Http\Requests\Apply\CreateRequest;
use App\Http\Requests\Apply\MessageBodyDto;
use App\Http\Requests\Apply\Start\SaveRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\CreateApplyInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartPriorConsultationParameterInterface;
use Ncc01\Notification\Application\Usecase\SendStartPriorConsultationNotificationInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionStartApplyInterface;

/**
 * SaveController
 *
 * @package App\Http\Controllers\Apply\Start
 */
class SaveController
{
    public function __construct(
        private CreateApplyInterface $createApply,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private SendStartPriorConsultationNotificationInterface $sendStartPriorConsultationNotification,
        private ValidatePermissionStartApplyInterface $validatePermissionStartApply
    ) {
    }

    public function __invoke(SaveRequest $request, ?int $applyId = null)
    {
        //権限：申出者アカウント以外は利用不可
        if (!$this->validatePermissionStartApply->__invoke($applyId)) {
            abort(403);
        }
        //保存処理
        $applyId = $this->createApply->__invoke(
            $request->createParameter(),
            $applyId
        );

        //通知の送信
        $this->sendStartPriorConsultationNotification->__invoke(
            $this->createNotificationParameter(
                $applyId,
                $request->createMessageDto()
            )
        );

        //申出一覧画面へリダイレクト
        return Redirect::route('apply.lists.my_list');
    }

    private function createNotificationParameter(
        int $applyId,
        MessageBodyDto $dto
    ): SendStartPriorConsultationParameterInterface {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
        /** @var SendStartPriorConsultationParameterInterface $parameter */
        $parameter = App::make(SendStartPriorConsultationParameterInterface::class);
        $parameter->setDto($dto);
        $parameter->setApplyId($applyId);
        $parameter->setSenderUserId($authenticatedUser->getId());
        $parameter->setSenderUserName($authenticatedUser->getName());
        return $parameter;
    }
}
