<?php

/**
 * Balocco Inc.
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 株式会社バロッコはシステム設計・開発会社として、
 * 社員・顧客企業・パートナー企業と共に企業価値向上に全力を尽くします
 *
 * 1. プロフェッショナル集団として人間力・経験・知識を培う
 * 2. システム設計・開発を通じて、顧客企業の成長を活性化する
 * 3. 顧客企業・パートナー企業・弊社全てが社会的意義のある事業を営み、全てがwin-winとなるビジネスをする
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 本社所在地
 * 〒101-0032　東京都千代田区岩本町2-9-9 TSビル4F
 * TEL:03-6240-9877
 *
 * 大阪営業所
 * 〒530-0063　大阪府大阪市北区太融寺町2-17 太融寺ビル9F 902
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Models;

use App\Common\JsonDecode;
use App\Common\ReadOnlyNullableArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Class Apply
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $type_id
 * @property string $applicant_name
 * @property string $subject
 * @property string $affiliation
 * @property string $department
 * @property int $status
 * @property User $user
 *
 */
class Apply extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    /** @var JsonDecode $jsonDecode */
    private $jsonDecode;

    /**
     * Apply constructor.
     * @param array $attributes
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->jsonDecode = App::make(JsonDecode::class);
    }

    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            [
                'apply_users' => $this->applyUsers()->get()->toArray(),
                'unread_secretariat_messages' => $this->unreadSecretariatMessages()->toArray(),
                'unread_applicant_messages' => $this->unreadApplicantMessages()->toArray()
            ]
        );
    }

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sourceApplyHistory()
    {
        return $this->hasOne(ApplyHistory::class, 'source_apply_id');
    }

    /**
     * applyUsers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function applyUsers()
    {
        return $this->hasMany(ApplyUser::class);
    }

    public function unreadApplicantMessages()
    {
        return $this->user->unreadNotifications->where('apply_id', $this->id);
    }

    /**
     * unreadSecretariatMessages
     *
     * @return \Illuminate\Notifications\DatabaseNotificationCollection|mixed
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function unreadSecretariatMessages()
    {
        /** @var User $secretariatUser */
        $secretariatUser = User::find(2);
        return $secretariatUser->unreadNotifications->where('apply_id', $this->id);
    }

    /**
     * get4AreaPrefecturesAttribute
     * jsonのキャスト
     *
     * @param $value
     * @return mixed
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function get4AreaPrefecturesAttribute($value)
    {
        return $this->jsonDecode->__invoke($value);
    }

    /**
     * get4RangeOfAgeAttribute
     * jsonのキャスト
     *
     * @param $value
     * @return mixed
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function get4RangeOfAgeAttribute($value)
    {
        return $this->jsonDecode->__invoke($value);
    }

    /**
     * get7SecurityAnswersAttribute
     * jsonのキャスト
     * @param $value
     * @return ReadOnlyNullableArray
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function get7SecurityAnswersAttribute($value)
    {
        return $this->jsonDecode->__invoke($value, true);
    }

    /**
     * get7SecurityRemarksAttribute
     * jsonのキャスト
     * @param $value
     * @return ReadOnlyNullableArray
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function get7SecurityRemarksAttribute($value)
    {
        return $this->jsonDecode->__invoke($value, true);
    }
}
