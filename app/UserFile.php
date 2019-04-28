<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    protected $fillable = [
        'user_id', 'file_id'
    ];
    public $timestamps = false;
}
