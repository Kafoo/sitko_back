<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;
use App\Traits\Imageable;
use App\Traits\Relationable;
use App\Traits\Taggable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Cerbero\QueryFilters\FiltersRecords;

class Place extends GlobalModel
{
	use HasFactory;
	use Imageable ;
  use Taggable, Relationable;
	use FiltersRecords;

	/**
		* The attributes that are mass assignable.
		*
		* @var array
		*/
	protected $fillable = [
		'name',
		'description',
		'author_id',
		'location',
		'visibility'
	];

protected $casts = [
    'location' => 'json',
];

	/**
		* The attributes that should be hidden for arrays.
		*
		* @var array
		*/
	protected $hidden = [
			'created_at',
			'updated_at'
	];

	public function author()
	{
			return $this->belongsTo('App\Models\User', 'author_id');
	}

	public function members()
	{
			return $this->belongsToMany('App\Models\User');
	}

	public function projects()
	{
			return $this->hasMany('App\Models\Project');
	}

	public function active_projects()
	{
			return $this->hasMany('App\Models\Project')->where('is_done', 0);
	}

	public function events()
	{
			return $this->hasMany('App\Models\Event');
	}

	public function notes()
	{
			return $this->hasMany('App\Models\Note');
	}

	public function caldates()
	{
			return $this->hasMany('App\Models\Caldate');
	}

	public static function create(array $attributes = [])
	{

			$model = static::query()->create($attributes + ['author_id' => Auth::id()]);

			$model->storeImage($attributes['image']);

			$model->storeTags($attributes['tags']);

			return $model;

	}

	public function update(array $attributes = [], array $options = [])
	{

			$response = parent::update($attributes, $options);

			$this->updateImage($attributes['image']);

			$this->updateTags($attributes['tags']);

			return $response;

	}

	public function delete()
	{

			$this->deleteImage();

			$this->deleteTags();

			$this->projects->each->delete();

			$this->events->each->delete();

			$this->notes->each->delete();

			$this->clearRelationships();

			$this->clearNotifications();

			$response = parent::delete();

			return $response;
	}

}
