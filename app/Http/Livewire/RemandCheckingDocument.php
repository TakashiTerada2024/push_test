<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RemandCheckingDocument extends Component
{
    public $applyId;
    public $applySubject;
    public $confirming;

    public function render()
    {
        return view('livewire.remand-checking-document');
    }

    public function openModal()
    {
        $this->confirming = true;
    }
}
