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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Http\Livewire;

use App\Services\ApplyMemoSubstringExtractorService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyMemoInterface;
use Ncc01\Memo\Application\Usecase\SaveApplyMemoInterface;

class MemoModal extends Component
{
    /** @var bool $showModal */
    public $showModal = false;
    /** @var int $applyId */
    public $applyId;
    /** @var string|null $memo */
    public $memo = null;
    /** @var string $firstFewCharacters */
    public $firstFewCharacters = '';

    /**
     * mount
     *
     * @return void
     */
    public function mount(): void
    {
        $this->storeFirstFewCharacters();
    }

    /**
     * render
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return View::make('livewire.memo-modal', [
            'applyId' => $this->applyId,
            'statusClass' => $this->statusClass(),
            'memo' => $this->memo
        ]);
    }

    /**
     * openModal
     *
     * @return void
     */
    public function openModal(): void
    {
        $this->showModal = true;
    }

    /**
     * closeModal
     *
     * @return void
     */
    public function closeModal(): void
    {
        $this->showModal = false;
    }

    /**
     * statusClass
     *
     * @return string
     */
    public function statusClass(): string
    {
        if (!is_null($this->memo) && $this->memo !== '') {
            return 'memo-icon-active';
        }
        return 'memo-icon-inactive';
    }

    /**
     * storeFirstFewCharacters
     * メモの先頭数文字をセット
     *
     * @return void
     */
    public function storeFirstFewCharacters(): void
    {
        $extractor = App::make(ApplyMemoSubstringExtractorService::class);
        $this->firstFewCharacters = $extractor->__invoke($this->memo);
    }

    /**
     * save
     * メモ保存
     *
     * @return void
     */
    public function save(
        ValidatePermissionModifyApplyMemoInterface $validatePermissionModifyApplyMemo,
        SaveApplyMemoInterface $saveApplyMemo
    ): void {
        if (!$validatePermissionModifyApplyMemo->__invoke()) {
            return;
        }
        if (is_null($this->memo)) {
            return;
        }

        try {
            DB::beginTransaction();
            $saveApplyMemo->__invoke($this->applyId, $this->memo);
            DB::commit();

            $this->afterSave();
            session()->flash('message', '保存しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $this->addError('memo', 'メモの保存に失敗しました。');
        }
    }

    /**
     * afterSave
     * メモ保存後の作業
     *
     * @return void
     */
    public function afterSave(): void
    {
        $this->storeFirstFewCharacters();
        // エラーログリセット
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
