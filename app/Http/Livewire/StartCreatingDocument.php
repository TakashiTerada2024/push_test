<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StartCreatingDocument extends Component
{
    public $applyId;
    public $applySubject;
    public $confirming;

    public function render()
    {
        return view('livewire.start-creating-document');
    }

    public function openModal()
    {
        $this->confirming = true;
    }
}
