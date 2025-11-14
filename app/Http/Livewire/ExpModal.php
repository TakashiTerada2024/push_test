<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ExpModal extends Component
{
    public $expModal = false;
    public $title = '';
    public $exp = '';

    public function showModal()
    {
        $this->expModal = true;
    }

    public function closeModal()
    {
        $this->expModal = false;
    }

    public function render()
    {
        return view('livewire.exp-modal');
    }
}
