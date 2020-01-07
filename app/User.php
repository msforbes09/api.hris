<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ApiResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type_id', 'name', 'username', 'email', 'password', 'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_type()
    {
        return $this->belongsTo('App\UserType');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ApiResetPasswordNotification($token, $this->email));
    }

    public function sendWelcomeNotification($password)
    {
        $this->notify(new WelcomeNotification($this, $password));
    }
}
