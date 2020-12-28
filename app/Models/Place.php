<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class Place extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
    	'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function storeImage($image){

        $imageModel = new Image();
        $imageModel->cloudinary($image);
        $this->image = $this->image()->save($imageModel);
    }

		public function projects()
		{
		    return $this->hasMany('App\Models\Project');
		}

		public function events()
		{
		    return $this->hasMany('App\Models\Event');
		}

		public function image()
		{
		    return $this->morphOne('App\Models\Image', 'imageable');
		}

		public function tags()
		{
		    return $this->morphMany('App\Models\Tag', 'tagable');
		}

}
