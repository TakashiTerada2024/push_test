<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Illuminate\Support\Facades\Http;

class GenerateSkipUrlModal extends Component
{
    /**
     * モーダルの表示状態
     * @var bool
     */
    public bool $isOpen = false;

    /**
     * 申出種別ID
     * @var int|null
     */
    public ?int $applyTypeId = null;

    /**
     * 生成されたスキップURLテキスト
     * @var string
     */
    public string $skipUrlText = '';

    /**
     * モーダルを開く
     *
     * @return void
     */
    public function openModal(): void
    {
        $this->isOpen = true;
    }

    /**
     * モーダルを閉じる
     *
     * @return void
     */
    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->reset(['applyTypeId', 'skipUrlText']);
    }

    /**
     * スキップURLを生成する
     *
     * @return void
     */
    public function generateUrl(): void
    {
        $this->validate([
            'applyTypeId' => 'required|integer|min:1|max:4',
        ], [
            'applyTypeId.required' => '申出種別が選択されていません。',
            'applyTypeId.integer' => '申出種別の値が不正です。',
            'applyTypeId.min' => '申出種別の値が不正です。',
            'applyTypeId.max' => '申出種別の値が不正です。',
        ]);

        // APIを呼び出す
        $response = Http::withToken(csrf_token())
            ->post(route('api.apply.skip-url.generate'), [
                'apply_type_id' => $this->applyTypeId,
            ]);

        if ($response->successful()) {
            $this->skipUrlText = $response->json('data.text_to_copy');
        } else {
            session()->flash('error', 'スキップURLの生成に失敗しました。');
        }
    }

    /**
     * コンポーネントのレンダリング
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.generate-skip-url-modal', [
            'applyTypes' => (new ApplyTypes())->listOfName(),
        ]);
    }
}
