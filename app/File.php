<?php

namespace App;

use App\Traits\SignatureTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use SignatureTrait;

    protected $fillable = [
        'id', 'filename', 'path', 'size', 'mime'
    ];
    public $incrementing = false;

    protected $appends = ['details'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_files',
            'file_id', 'user_id');
    }

    public function getSizeAttribute()
    {
        $size = $this->attributes['size'];
        $size = (int)$size;
        $base = log($size) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

        return round(pow(1024, $base - floor($base)), 2) . $suffixes[floor($base)];
    }

    public function getDetailsAttribute()
    {
        $res = $this->getPDFDetails($this->attributes['filename'], $this->attributes['path']);

        if ($res['status'] != 'none') {
            return $res['result'][0];
        } else {
            return $res;
        }
    }
}
