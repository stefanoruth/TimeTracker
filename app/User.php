<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'object',
    ];

    public function timeRegistrations()
    {
        return $this->hasMany(TimeRegistration::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->settings = User::baseSettings();
        });
    }

    public static function baseSettings()
    {
        return [
            'launch' => 30, // min
            'work' => 2250, // 37h 30min
            'start' => '08:00',
            'end' => '16:00',
            'days' => [ // Working days
                'monday' => true,
                'tuesday' => true,
                'wednesday' => true,
                'thursday' => true,
                'friday' => true,
                'saturday' => false,
                'sunday' => false,
            ],
        ];
    }
}
