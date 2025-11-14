<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ncc01\Apply\Enterprise\Entity\ApplicationSkipUrl as ApplicationSkipUrlEntity;

/**
 * ApplicationSkipUrl Eloquentモデル
 *
 * @property int $id
 * @property string $ulid
 * @property int $apply_type_id
 * @property int $created_by
 * @property DateTimeInterface|null $expired_at
 * @property bool $is_used
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 *
 * @property User $creator
 */
class ApplicationSkipUrl extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'application_skip_urls';

    /**
     * @var array
     */
    protected $fillable = [
        'ulid',
        'apply_type_id',
        'created_by',
        'expired_at',
        'is_used',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_used' => 'boolean',
        'expired_at' => 'datetime',
    ];

    /**
     * 作成者（事務局ユーザー）へのリレーション
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Eloquentモデルからエンティティに変換
     *
     * @return ApplicationSkipUrlEntity
     */
    public function toEntity(): ApplicationSkipUrlEntity
    {
        return new ApplicationSkipUrlEntity(
            $this->id,
            $this->ulid,
            $this->apply_type_id,
            $this->created_by,
            $this->expired_at,
            $this->is_used,
            $this->created_at,
            $this->updated_at
        );
    }

    /**
     * エンティティからEloquentモデルを作成または更新
     *
     * @param ApplicationSkipUrlEntity $entity
     * @return self
     */
    public static function fromEntity(ApplicationSkipUrlEntity $entity): self
    {
        $model = $entity->getId()
            ? self::findOrFail($entity->getId())
            : new self();

        $model->fill([
            'ulid' => $entity->getUlid(),
            'apply_type_id' => $entity->getApplyTypeId(),
            'created_by' => $entity->getCreatedBy(),
            'expired_at' => $entity->getExpiredAt(),
            'is_used' => $entity->isUsed(),
        ]);

        return $model;
    }
}
