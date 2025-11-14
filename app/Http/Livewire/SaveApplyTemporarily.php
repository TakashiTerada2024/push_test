<?php

namespace App\Http\Livewire;

use Livewire\Component;

/**
 * Class SaveApplyTemporarily
 * 申出情報の一時保存ボタン
 * ボタン押下時、事務局への通知を行うかどうかを確認するモーダルを表示する
 * @package App\Http\Livewire
 */
class SaveApplyTemporarily extends Component
{
    public $confirming;
    public $notifyFlag = 1;
    public $isLocked = false;

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function mount($isLocked = false)
    {
        $this->isLocked = $isLocked;
    }

    public function render()
    {
        return view('livewire.save-apply-temporarily');
    }

    public function confirmSendToSecretariat()
    {
        if ($this->isLocked) {
            return;
        }
        //フラグを立てる
        $this->confirming = true;
    }
}
