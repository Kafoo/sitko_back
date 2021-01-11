<?php
 
namespace App\Traits;
 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;

use function PHPUnit\Framework\throwException;

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

	}
 
	public function deleteImage(){

    if ($this->image) {
		
	    $public_id = $this->image->public_id;

      if($public_id){
        Cloudinary::destroy($public_id);
      }
      $this->image()->delete();
			$this->load('image');
    }

	}

}