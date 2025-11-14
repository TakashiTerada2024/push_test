<?php

namespace App\Http\Livewire;

use App\Common\ReadOnlyNullableArray;
use Illuminate\Support\Collection;
use Livewire\Component;

class ApplyUsers extends Component
{
    public $numberOfUsers;
    public $applyUsers;
    public $isLocked;

    public function render()
    {
        return view('livewire.apply-users', ['applyUsers' => $this->applyUsers]);
    }

    /**
     * mount
     *
     * @param Collection $formValues
     * @param bool $isLocked
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function mount(Collection $formValues, bool $isLocked = false)
    {
        $this->numberOfUsers = $formValues->get('3_number_of_users') ?? 1;
        $this->applyUsers = $formValues->get('apply_users');
        $this->isLocked = $isLocked;
    }
}
