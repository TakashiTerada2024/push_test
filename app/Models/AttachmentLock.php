<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AttachmentLock
 *
 * @property int $id
 * @property int $apply_id
 * @property int $attachment_type_id
 * @property bool $is_locked
 */
class AttachmentLock extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'attachment_locks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<array-key, string>
     */
    protected $fillable = [
        0 => 'apply_id',
        1 => 'attachment_type_id',
        2 => 'is_locked',
        3 => 'last_updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<array-key, mixed>
     */
    protected $casts = [
        'is_locked' => 'boolean',
    ];
}
