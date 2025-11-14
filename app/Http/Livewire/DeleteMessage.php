<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;

class DeleteMessage extends Component
{
    protected $listeners = [
        'delete' => 'delete',
        'showModal' => 'showModal',
    ];
    protected $rules = [
        'messageBody' => ['required'],
        'notificationId' => ['required', 'uuid'],
    ];

    //ややこしいが、validation()でのエラーメッセージ定義。applyのmessagesとは違う
    protected $messages = [
        'messageBody.required' => 'メッセージ内容は入力必須です',
        'notificationId.uuid' => '更新できません。管理者にお問い合わせください（UUIDエラー）',
    ];

    public string $messageBody;
    public string $notificationId;
    public $deleteMessage = false;

    public function render()
    {
        return view('livewire.delete-message');
    }

    public function showModal()
    {
        $this->deleteMessage = true;
    }

    /**
     * delete
     * メッセージ削除確認ダイアログを表示する
     *
     * @param string $notificationId
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function delete(string $notificationId)
    {
        if (!Str::isUuid($notificationId)) {
            $errors = $this->getErrorBag();
            $errors->add('notificationId', $this->messages['notificationId.uuid']);
        }
        $this->notificationId = $notificationId;
        $this->messageBody = Lang::get('apply.message.deleted');

        $this->showModal();
    }

    /**
     * submit
     * メッセージ編集内容をvalidate後、メッセージ更新・送信処理へリダイレクトする
     * （レコード削除ではなく、「削除」文言で更新する）
     *
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function submit()
    {
        $validateData = $this->validate();

        return redirect(
            route(
                'message.apply.send.edit',
                ['notificationId' => $this->notificationId]
            ) . '?messageBody=' . rawurlencode($validateData['messageBody'])
        );
    }
}
