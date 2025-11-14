<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;

class ChangeApplyType extends Component
{
    public $confirming;

    public $applyId;
    public $applyTypeId;
    public $applySubject;

    /** @var ApplyTypes $applyTypes */
    private $applyTypes;


    public function mount(ApplyTypes $applyTypes)
    {
        $this->applyTypes = $applyTypes;
    }

    public function render()
    {
        return view('livewire.change-apply-type');
    }

    public function openApplyTypeForm()
    {
        $this->confirming = true;
    }

    public function hoge()
    {
        return 1;
    }
}
