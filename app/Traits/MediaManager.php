<?php
 
namespace App\Traits;
 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;

trait MediaManager {
 
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
    print_r($this);
	}
 
	public function deleteImage(){

		if ($this->image) { 

			if ($this->image->public_id) {
				Cloudinary::destroy($this->image->public_id);
			}
			$this->image()->delete();
      print_r($this);
		}
	}

}