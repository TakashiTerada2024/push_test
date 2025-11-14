<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @property int $id
 * @property string $name
 * @property int $role_id
 * @property \Illuminate\Notifications\DatabaseNotificationCollection $unreadNotifications
 * @package App\Models
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * getId
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * getName
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getName(): string
    {
        return (string)$this->name;
    }
}
