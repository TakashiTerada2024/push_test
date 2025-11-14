<?php

namespace App\Http\View\Composers\Contents\Apply\Lock;

use GitBalocco\LaravelUiUtils\Http\IdentityHandler;
use GitBalocco\LaravelUiViewComposer\BasicComposer;
use GitBalocco\LaravelUiViewComposer\Contract\FormValueApplier;
use GitBalocco\LaravelUiViewComposer\Contract\FormValueBuilder;
use GitBalocco\LaravelUiViewComposer\Contract\ViewComposerInterface;
use GitBalocco\LaravelUiViewComposer\Contract\ViewParameterCreator;
use GitBalocco\LaravelUiViewComposer\FormComposer;
use GitBalocco\LaravelUiViewComposer\FormValue\Applier\OnUpdateApplier;
use GitBalocco\LaravelUiViewComposer\FormValue\Builder\EloquentFormValueBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Ncc01\Apply\Application\Usecase\RetrieveAttachmentLocksInterface;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;

/**
 * ManagementComposer
 *
 * @package App\Http\View\Composers\Contents\Apply\Lock
 */
class ManagementComposer extends FormComposer implements ViewComposerInterface, ViewParameterCreator
{
    /**
     * ManagementComposer constructor.
     *
     * @param Request $request
     * @param RetrieveAttachmentLocksInterface $retrieveAttachmentLocks
     * @param RetrieveScreenLocksInterface $retrieveScreenLocks
     */
    public function __construct(
        Request $request,
        private RetrieveAttachmentLocksInterface $retrieveAttachmentLocks,
        private RetrieveScreenLocksInterface $retrieveScreenLocks
    ) {
        parent::__construct($request);
    }

    /**
     * createParameter
     *
     * @return array
     */
    public function createParameter(): array
    {
        $applyId = $this->getRequest()->route('applyId');
        return [
            'applyId' => $applyId
        ];
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function init(Request $request, View $view): void
    {
        $applyId = (int)$this->getRequest()->route('applyId');
        //初期表示用のロジックをインスタントクラスで実装
        $applyer = new class (
            $applyId,
            $this->retrieveScreenLocks,
            $this->retrieveAttachmentLocks
        ) implements FormValueApplier {
            public function __construct(
                private int $applyId,
                private RetrieveScreenLocksInterface $retrieveScreenLocks,
                private RetrieveAttachmentLocksInterface $retrieveAttachmentLocks
            ) {
            }

            public function shouldApply(): bool
            {
                //デフォルトロジックなので、常にtrue
                return true;
            }

            public function getBuilder(): FormValueBuilder
            {
                return new class (
                    $this->applyId,
                    $this->retrieveScreenLocks,
                    $this->retrieveAttachmentLocks
                ) implements FormValueBuilder {
                    public function __construct(
                        private int $applyId,
                        private RetrieveScreenLocksInterface $retrieveScreenLocks,
                        private RetrieveAttachmentLocksInterface $retrieveAttachmentLocks
                    ) {
                    }

                    public function build(): Collection
                    {
                        //ココに、DBからの取得を追加実装
                        return collect([
                            "screen_locks" => $this->retrieveScreenLocks->__invoke($this->applyId),
                            "attachment_locks" => $this->retrieveAttachmentLocks->__invoke($this->applyId)
                        ]);
                    }
                };
            }
        };
        $this->addFormValuesApplier($applyer);
    }
}
