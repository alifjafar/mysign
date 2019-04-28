<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'isAdmin', 'address', 'phone', 'sign', 'username'
    ];

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
    ];

    protected $appends = ['avatar'];

    public function files()
    {
        return $this->belongsToMany(File::class, 'user_files',
            'user_id', 'file_id');
    }

    public function request()
    {
        return $this->hasMany(Requester::class, 'user_id');
    }

    public function recipient()
    {
        return $this->hasMany(Requester::class, 'recipient_id');
    }

    public function getAvatarAttribute()
    {
        $hash = md5(strtolower(trim($this['email']))) . '.jpeg' . '?s=106&d=mm&r=g';
        return "https://secure.gravatar.com/avatar/$hash";
    }

    public function getRoleAttribute()
    {
        return $this->attributes['isAdmin'] == 1 ? 'admin' : 'user';
    }
}
