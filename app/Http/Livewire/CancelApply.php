<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CancelApply extends Component
{
    public $applyId;
    public $applySubject;
    public $confirming;

    public function render()
    {
        return view('livewire.cancel-apply');
    }

    public function openModal()
    {
        $this->confirming = true;
    }
}
