<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaManager;


class Place extends Model
{
	use HasFactory;
	use MediaManager;

	/**
		* The attributes that are mass assignable.
		*
		* @var array
		*/
	protected $fillable = [
		'name',
		'description'
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
	
	public function projects()
	{
			return $this->hasMany('App\Models\Project');
	}

	public function events()
	{
			return $this->hasMany('App\Models\Event');
	}

	public function caldates()
	{
			return $this->hasMany('App\Models\Caldate');
	}

	public function image()
	{
			return $this->morphOne('App\Models\Image', 'imageable');
	}

	public function tags()
	{
			return $this->morphToMany('App\Models\Tag', 'taggable');
	}

}
