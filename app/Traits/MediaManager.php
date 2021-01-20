<?php
 
namespace App\Traits;
 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image;
use App\Models\Tag;

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

	public function updateTags($tags){

		$newTags = [];

		foreach ($tags as $tag) {
				$isNew = true;
				foreach ($this->tags as $oldTag) {
						if ($tag['title'] == $oldTag->title){
								$isNew = false;
						}
				}

				if ($isNew) {
						if (isset($tag['id'])) {
								$newTags[] = $tag['id'];
						}else{
								$tagModel = new Tag($tag);
								if($tag['category']){
										$tagModel->category_id = $tag['category']['id'];
								}
								$tagModel->save();
								$newTags[] = $tagModel->id;
						}
				}
		}

		foreach($this->tags as $tag){
				$isUnused = true;
				foreach($tags as $newTag){
						if ($tag->title == $newTag['title']){
								$isUnused = false;
						}
				}

				if ($isUnused) {
						if ($tag->custom == '1') {
								$tag->delete();
						}
				}else{
						$newTags[] = $tag->id;
				}
		}

		$this->tags()->sync($newTags);
		$this->load('tags');
	
	}

}