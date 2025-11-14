<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ScreenLock
 *
 * @property int $id
 * @property int $apply_id
 * @property int $screen_id
 * @property bool $is_locked
 */
class ScreenLock extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'screen_locks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<array-key, string>
     */
    protected $fillable = [
        'apply_id',
        'screen_id',
        'is_locked',
        'last_updated_by',
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
