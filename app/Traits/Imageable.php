<?php
 
namespace App\Traits;

use App\Exceptions\CustomException;
use App\Jobs\UploadImage;
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


				$imageModel = new Image();
				$imageModel->setDownloading($image);
	
				$this->image()->save($imageModel);

				dispatch(new UploadImage($imageModel, $image));

				$this->load('image');

			} catch (\Throwable $th) {

				throw new CustomException(trans('crud.fail.image.creation'));

			}
		}
	}

    public function changeImage($newImage){

			// If we have a string (Blob)
			if (gettype($newImage) === "string" ) {

				//Delete old image
				$this->deleteImage();
				$this->storeImage($newImage);


			//Else, no image or same image
			}else if (!$newImage){
				$this->deleteImage();
			}
    }

	public function updateImage($newImage){
	
		try {

			if ($newImage) {

					if ($this->image){
							$this->changeImage($newImage);

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

				if($public_id && $public_id != "downloading"){
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