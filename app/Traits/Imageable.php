<?php
 
namespace App\Traits;

use App\Exceptions\CustomException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;

trait Imageable {

	public function image()
	{
			return $this->morphOne('App\Models\Image', 'imageable');
	}

	public function storeImage($image){

		if ($image) {

			try {

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

			} catch (\Throwable $th) {

				throw new CustomException(trans('crud.fail.image.creation'));

			}
		}
	}

	public function updateImage($newImage){
	
		try {

			if ($newImage) {

					if ($this->image){
							$this->image->change($newImage);
					}else{
							$this->storeImage($newImage);
					}
			}else{
					$this->deleteImage();
			}

		} catch (\Throwable $th) {

			throw new CustomException(trans('crud.fail.image.update'));

		}
	}

	public function deleteImage(){

    if ($this->image) {
		
			try {

				$public_id = $this->image->public_id;

				if($public_id){
					Cloudinary::destroy($public_id);
				}
				$this->image()->delete();
				$this->load('image');

			} catch (\Throwable $th) {

				throw new CustomException(trans('crud.fail.image.deletion'));

			}
    }
	}

}