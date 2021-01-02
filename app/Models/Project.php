<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Traits\MediaManager;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;


class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title',
    	'type',
    	'description',
        'place_id'
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

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function events()
    {
        return $this->morphMany('App\Models\Event', 'child');
    }

    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }


    public function storeEvents($events){

        $newEvents = [];

        foreach ($events as $event) {
            $eventModel = new Event($event);
            $eventModel->type('project');
            $newEvents[] = $eventModel;
        }

        $this->events = $this->events()->saveMany($newEvents);
    }

	public function storeImage($image){

		// If we have a string (Blob), upload it to cloudinary
		if (gettype($image) === "string" ) {
			$imageModel = new Image();
			$imageModel->upload($image);
			$this->image = $this->image()->save($imageModel);

		// Else, we should already have a proper image model 
		}else{
			$imageModel = new Image($image);
			$this->image = $this->image()->save($imageModel);
		}
	}
 
	public function deleteImage(){

		if ($this->image) { 

			if ($this->image->public_id) {
				Cloudinary::destroy($this->image->public_id);
			}
			$this->image()->delete();
		}
	}

}
