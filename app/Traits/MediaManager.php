<?php
 
namespace App\Traits;
 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;
use App\Models\Caldate;

trait MediaManager {


	public function storeCaldates($caldates){

			$newCaldates = [];

			foreach ($caldates as $caldate) {
					$caldateModel = new Caldate($caldate);
					$caldateModel->place_id = $this->place_id;
					$newCaldates[] = $caldateModel;
			}

			$this->caldates = $this->caldates()->saveMany($newCaldates);

			$this->load('caldates');

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

		$this->load('image');

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