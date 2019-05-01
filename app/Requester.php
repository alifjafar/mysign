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

    public $timestamps = false;


    public function status()
    {
        return $this->hasMany(RequesterStatus::class, 'requester_id')
            ->orderBy('created_at', 'desc');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class,'file_id');
    }

}
