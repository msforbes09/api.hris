<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, Notifiable, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_type_id', 'branch_id', 'name', 'username', 'email', 'password', 'active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isActive()
    {
        return (bool) $this->active;
    }

    public function userType()
    {
        return $this->belongsTo('App\UserType');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }

    public function sendWelcomeNotification($password)
    {
        $this->notify(new WelcomeNotification($this, $password));
    }
}
