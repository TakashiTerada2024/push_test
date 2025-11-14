<?php

namespace App\View\Components;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Specification\Validation\Contracts\ValidationResultInterface;

class ApplyValidationInfo extends Component
{
    /** @var ValidationResultInterface $validationResult */
    public ValidationResultInterface $validationResult;
    /** @var bool $hasAttachment */
    public bool $hasAttachment = false;

    /**
     * @param ValidationResultInterface $validationResult
     * @param bool $hasAttachment
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)  2025-03-05 yamamoto
     */
    public function __construct(ValidationResultInterface $validationResult, bool $hasAttachment = false)
    {
        $this->validationResult = $validationResult;
        $this->hasAttachment = $hasAttachment;
    }

    public function render()
    {
        return View::make('components.apply-validation-info');
    }
}
