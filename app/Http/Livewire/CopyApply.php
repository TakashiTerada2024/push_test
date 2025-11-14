<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Gateway\Repository\Apply\ApplyRepository;
use App\Services\ApplyHistoryService;
use Illuminate\Support\Facades\DB;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;
use Ncc01\Attachment\Application\Usecase\RetrieveAttachmentsOfApplyAndTypeInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCreatingDocumentParameterInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\Apply\Application\Usecase\RetrieveApplicantByIdInterface;
use Ncc01\Notification\Application\Usecase\SendStartCreatingDocumentInterface;
use App\Gateway\Repository\ApplyUser\ApplyUserRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

/**
 * TODO:リファクタリング
 * PHPMD warning: CouplingBetweenObjects The class CopyApply has a coupling between objects value of 14.
 * Consider to reduce the number of dependencies under 13.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CopyApply extends Component
{
    public $applyId;

    public function render()
    {
        return view('livewire.copy-apply');
    }

    public function cloneApply(
        ApplyRepository $applyRepository,
        ApplyUserRepository $applyUserRepository,
        ApplyHistoryService $applyHistoryService
    ) {
        if (!$applyRepository->existAccepted($this->applyId)) {
            abort(404);
        }
        try {
            DB::beginTransaction();
            $authenticatedUser = App::make(RetrieveAuthenticatedUserInterface::class);
            $apply = $applyRepository->cloneApplyById($this->applyId);
            $applyHistoryService->createHistory($apply->id, $this->applyId);
            $applyUserRepository->cloneBySourceApplyId($this->applyId, $apply->id);
            $this->cloneAttachments($apply->id);
            $this->sendNotification($apply->id, $authenticatedUser);
            DB::commit();
            return $this->redirectToMyListWithMessage(__('apply.message.change_request_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->redirectToMyListWithMessage(__('apply.message.change_request_failed'), 'danger');
        }
    }

    /**
     * @param int $applyId
     * @param RetrieveAuthenticatedUserInterface $authenticatedUser
     * @return void
     */
    protected function cloneAttachments(int $applyId)
    {
        $attachmentType = App::make(AttachmentTypes::class);
        $retrieveAttachmentsOfApply = App::make(RetrieveAttachmentsOfApplyAndTypeInterface::class);
        $attachments = $retrieveAttachmentsOfApply->__invoke(
            applyId: $this->applyId,
            isGroup: false,
            typeList: $attachmentType->getApplicantAttachmentList(),
            statusList: [AttachmentStatuses::APPROVED]
        );

        foreach ($attachments as $attachment) {
            $saveAttachmentParameter = App::make(SaveAttachmentParameterInterface::class);
            $saveAttachmentParameter->setApplyId($applyId);
            $saveAttachmentParameter->setUserId($attachment['user_id']);
            $saveAttachmentParameter->setClientOriginalName('継続_' . $attachment['name']);
            $saveAttachmentParameter->setAttachmentTypeId($attachment['attachment_type_id']);
            $saveAttachmentParameter->setContent(Storage::get("attachment/" . $attachment['path']));

            $saveAttachment = App::make(SaveAttachmentInterface::class);
            $saveAttachment($saveAttachmentParameter);
        }
    }

    /**
     * @param $applyId
     * @param RetrieveAuthenticatedUserInterface $authenticatedUser
     * @return void
     */
    protected function sendNotification($applyId, RetrieveAuthenticatedUserInterface $authenticatedUser)
    {
        $applicant = App::make(RetrieveApplicantByIdInterface::class)->__invoke($applyId);

        /** @var SendStartCreatingDocumentParameterInterface $parameter */
        $parameter = App::make(SendStartCreatingDocumentParameterInterface::class);
        $parameter->setSenderUserId($authenticatedUser()->messageSenderId());
        $parameter->setSenderUserName($authenticatedUser()->messageSenderName());
        $parameter->setApplyId($applyId);

        $sendStartCreatingDocument = App::make(SendStartCreatingDocumentInterface::class);
        $sendStartCreatingDocument($applicant->getId(), $parameter);
    }

    /**
     * @param $message
     * @param $style
     */
    protected function redirectToMyListWithMessage($message, $style = 'success')
    {
        session()->flash('flash.banner', $message);
        session()->flash('flash.bannerStyle', $style);
        return $this->redirect('/apply/lists/my_list');
    }
}
