<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequesterStatus extends Model
{
    protected $fillable = [
        'requester_id', 'name'
    ];
}
