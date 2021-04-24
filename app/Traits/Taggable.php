<?php

namespace App\Traits;

use App\Exceptions\CustomException;
use App\Models\Tag;

trait Taggable
{

	public function tags()
	{
			return $this->morphToMany('App\Models\Tag', 'taggable')->withPivot('order');
	}

	public function storeTags($tags){
	
		try {

			$this->updateTags($tags);

		} catch (\Throwable $th) {

			throw new CustomException(trans('crud.fail.tags.creation'));

		}
	
	}

	public function updateTags($tags){

		try {

			$newTags = [];

			foreach ($tags as $tag) {
					$tagModel = new Tag($tag);
					$isNew = true;
					foreach ($this->tags as $oldTag) {
							if ($tagModel->title == $oldTag->title){
									$isNew = false;
							}
					}

					if ($isNew) {
							if (isset($tag['id'])) {
									$tagModel->id = $tag['id'];
									$newTags[] = $tagModel;
							}else{
									if($tag['category']){
											$tagModel->category_id = $tag['category']['id'];
									}
									$tagModel->save();
									$newTags[] = $tagModel;
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
							$newTags[] = $tag;
					}

			}

			$order = 1;
			$newTagsList = [];

			foreach ($tags as $requestTag) {
				foreach ($newTags as $newTag) {
					if ($newTag->title === $requestTag['title']) {
						$newTagsList[$newTag->id] = ['order' => $order];
					}
				}
				$order++;
			}

			$this->tags()->sync($newTagsList);
			$this->load('tags');
			
		} catch (\Throwable $th) {

			throw new CustomException(trans('crud.fail.tags.update'));

		}

	}

	public function deleteTags(){
	
		try {

			$this->updateTags([]);

		} catch (\Throwable $th) {

			throw new CustomException(trans('crud.fail.tags.creation'));

		}
	
	}

}
