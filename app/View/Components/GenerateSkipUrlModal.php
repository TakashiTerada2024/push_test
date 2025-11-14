<?php

namespace App\View\Components;

use Illuminate\View\Component;
use GitBalocco\KeyValueList\Contracts\KeyValueListable;

class GenerateSkipUrlModal extends Component
{
    /**
     * 申出種別の配列
     *
     * @var array
     */
    public $applyTypes;

    /**
     * Create a new component instance.
     *
     * @param KeyValueListable|array $applyTypes
     * @return void
     */
    public function __construct($applyTypes = [])
    {
        $this->applyTypes = $this->normalizeApplyTypes($applyTypes);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.generate-skip-url-modal');
    }

    /**
     * 申出種別の配列を正規化する
     *
     * @param KeyValueListable|array $applyTypes
     * @return array
     */
    private function normalizeApplyTypes($applyTypes): array
    {
        if ($applyTypes instanceof KeyValueListable) {
            $result = [];
            foreach ($applyTypes as $key => $value) {
                $result[$key] = $value;
            }
            return $result;
        }
        return $applyTypes;
    }
}
