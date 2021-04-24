<?php
 
namespace App\Traits;

use App\Exceptions\CustomException;
use App\Models\Caldate;

trait Caldatable {

    public function caldates()
    {
        return $this->morphMany('App\Models\Caldate', 'child');
    }

	public function storeCaldates($caldates){

		try {

			$newCaldates = [];

			foreach ($caldates as $caldate) {
					$caldateModel = new Caldate($caldate);
					$caldateModel->place_id = $this->place_id;
					$newCaldates[] = $caldateModel;
			}

			$this->caldates = $this->caldates()->saveMany($newCaldates);

		} catch (\Throwable $th) {

			throw new CustomException(trans('crud.fail.caldates.creation'));

		}
	}

	public function updateCaldates($caldates){

		try {
		
			$this->caldates->each->delete();

			$this->storeCaldates($caldates);

		} catch (\Throwable $th) {

			throw new CustomException(trans('crud.fail.caldates.update'));

		}


	}

	public function deleteCaldates(){
	
		try {
		
			$this->caldates->each->delete();

		} catch (\Throwable $th) {

			throw new CustomException(trans('crud.fail.caldates.deletion'));

		}

	}

}