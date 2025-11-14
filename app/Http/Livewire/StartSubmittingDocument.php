<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StartSubmittingDocument extends Component
{
    public $applyId;
    public $applySubject;
    public $confirming;

    public function render()
    {
        return view('livewire.start-submitting-document');
    }
    public function openModal()
    {
        $this->confirming = true;
    }
}
