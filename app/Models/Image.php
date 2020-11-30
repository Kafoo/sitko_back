<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'imageable_type',
        'imageable_id'
    ];


    public function cloudinary($img)
    {

        $cloudinary_response = Cloudinary::upload($img);

        $this->full = $cloudinary_response->getSecurePath();

        $parts = explode('upload/', $this->full);

        $this->medium = $parts[0].'upload/t_medium/'.$parts[1];
        $this->low_medium = $parts[0].'upload/t_low_medium/'.$parts[1];
        $this->thumb = $parts[0].'upload/t_thumb/'.$parts[1];
        $this->public_id = $cloudinary_response->getPublicId();

    }

    public function imageable()
    {
        return $this->morphTo();
    }

}
