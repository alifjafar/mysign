<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
    protected $fillable = [
        'user_id', 'file_id', 'requested', 'updated', 'recipient_id'
    ];

    protected $casts = [
        'requested' => 'datetime',
        'updated' => 'datetime'
    ];


    public function status()
    {
        return $this->hasMany(RequesterStatus::class, 'requester_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

}
