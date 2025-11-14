<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Ncc01\Messaging\Application\GatewayInterface\MessageRepositoryInterface;
use Illuminate\Support\Str;

class EditMessage extends Component
{
    protected $listeners = [
        'edit' => 'edit',
        'showModal' => 'showModal',
    ];

    protected $rules = [
        'messageBody' => ['required'],
        'notificationId' => ['required', 'uuid'],
    ];

    protected $messages = [
        'messageBody.required' => 'メッセージ内容は入力必須です',
        'notificationId.uuid' => '更新できません。管理者にお問い合わせください（UUIDエラー）',
    ];

    public string $messageBody;
    public string $notificationId;

    public $editMessage = false;

    public function showModal()
    {
        $this->editMessage = true;
    }

    public function closeModal()
    {
        $this->editMessage = false;
    }

    public function render()
    {
        return view('livewire.edit-message');
    }

    /**
     * edit
     * メッセージ編集ダイアログを表示する
     *
     * @param string $notificationId
     * @param string $messageBody
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function edit(string $notificationId, string $messageBody)
    {

        if (!Str::isUuid($notificationId)) {
            $errors = $this->getErrorBag();
            $errors->add('notificationId', $this->messages['notificationId.uuid']);
        }
        $this->notificationId = $notificationId;
        $this->messageBody = trim(rawurldecode($messageBody));

        $this->showModal();
    }

    /**
     * submit
     * メッセージ編集内容をvalidate後、メッセージ更新・送信処理へリダイレクトする
     *
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function submit()
    {
        $validateData = $this->validate();

        $url = route(
            'message.apply.send.edit',
            ['notificationId' => $this->notificationId]
        ) . '?messageBody=' . rawurlencode($validateData['messageBody']);
        return redirect($url);
    }
}
