<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Attachment701 extends Component
{
    /** @var mixed $numberOfEnvironment 予期しない入力を受け取った場合に例外が発生しないよう、int型としていない。 */
    public $numberOfEnvironment;
    public $attachments701;
    public $isLocked;

    public function render()
    {
        return view('livewire.attachment701');
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function mount(array $attachments701, bool $isLocked = false)
    {
        $this->numberOfEnvironment = (count($attachments701) > 0) ? count($attachments701) : 1;
        $this->attachments701 = $attachments701;
        $this->isLocked = $isLocked;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'numberOfEnvironment' && !$this->isLocked) {
            $this->filterNumberOfEnvironment();
        }
    }

    private function filterNumberOfEnvironment()
    {
        //英数字等の入力があった場合、入力された内容を拒否して数字に変更してしまう。
        if (!ctype_digit($this->numberOfEnvironment)) {
            $this->numberOfEnvironment = 1;
            return;
        }
        //1未満の入力は、1に変更
        if ($this->numberOfEnvironment < 1) {
            $this->numberOfEnvironment = 1;
            return;
        }
        //10より大きい入力は、10に変更
        if ($this->numberOfEnvironment > 10) {
            $this->numberOfEnvironment = 10;
            return;
        }
        return;
    }
}
