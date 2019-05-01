<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequesterStatus extends Model
{
    protected $table = 'requester_status';

    protected $fillable = [
        'requester_id', 'name'
    ];
}
