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

	public function storeImage($image){

		// If we have a string (Blob), upload it to cloudinary
		if (gettype($image) === "string" ) {
			$imageModel = new Image();
			$imageModel->cloudinary($image);
			$this->image = $this->image()->save($imageModel);

		// Else, we should already have a proper image model 
		}else{
			$imageModel = new Image($image);
			$this->image = $this->image()->save($imageModel);
		}
	}

	public function updateImage($image){

		$oldImage = $this->image;

		// If we have a string (Blob)
		if (gettype($image) === "string" ) {

			//Delete old image
			if ($oldImage->public_id) {
				Cloudinary::destroy($oldImage->public_id);
			}
			$oldImage->delete();

			//Store new image
			$newImage = new Image();
			$newImage->cloudinary($image);
			$this->image = $this->image()->save($newImage);

		//Else, generic image or same image
		}else{

			$newImage = new Image($image);
			
			if ($oldImage->full !== $newImage->full) {

				if ($oldImage->public_id) {
					Cloudinary::destroy($oldImage->public_id);
				}

				$this->image()->delete();
				$this->image()->save($newImage);
			}else{
				// Same images, do nothing
			}
		}
	}

	public function deleteImage(){

		$image = Image::where('imageable_id', $this->id)->get()[0];

		if (count($image->get()) > 0) {

			if ($image->public_id) {
				Cloudinary::destroy($image->public_id);
			}
			$image->delete();
		}
	}

}
