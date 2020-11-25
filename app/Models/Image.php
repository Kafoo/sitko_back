<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
    	'full',
        'medium',
        'low_medium',
    	'thumb',
    	'deletion',
        'imageable_id',
        'imageable_type'
    ];


    public function fill($cloudinary)
    {
        $this->full = $cloudinary->getSecurePath();

        $parts = explode('upload/', $this->full);

        $this->medium = $parts[0].'upload/t_medium/'.$parts[1];
        $this->low_medium = $parts[0].'upload/t_low_medium/'.$parts[1];
        $this->thumb = $parts[0].'upload/t_thumb/'.$parts[1];
        $this->public_id = $cloudinary->getPublicId();

    }

    public function imageable()
    {
        return $this->morphTo();
    }

}
