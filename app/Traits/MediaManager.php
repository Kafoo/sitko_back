<?php
 
namespace App\Traits;
 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;

trait MediaManager {
 
	public function storeImage($image){

		// If we have a string (Blob), upload it to cloudinary
		if (gettype($image) === "string" ) {
			$this->image = new Image();
			$this->image->upload($image);
			$this->image()->save($this->image);

		// Else, we should already have a proper image model 
		}else{
			$this->image = new Image($image);
			$this->image()->save($this->image);
		}
	}
 
	public function deleteImage(){

		if ($this->image) { 

			if ($this->image->public_id) {
				Cloudinary::destroy($this->image->public_id);
			}
			$this->image()->delete();
      $this->image = null;
		}
	}

}