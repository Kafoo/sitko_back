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

	}
 
	public function deleteImage(){

    $image = Image::where('imageable_id', $this->id); 
    $public_id = $image->get()[0]->public_id;

    if (count($image->get()) > 0) {

      if($public_id){
        Cloudinary::destroy($public_id);
      }
      $this->image()->delete();
    }

	}

}