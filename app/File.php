<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'id', 'filename', 'path', 'size', 'mime'
    ];
    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_files',
            'file_id','user_id');
    }
}
