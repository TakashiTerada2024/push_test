<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AcceptApply extends Component
{
    public $applyId;
    public $applySubject;
    public $confirming;

    public function render()
    {
        return view('livewire.accept-apply');
    }

    public function openModal()
    {
        $this->confirming = true;
    }
}
