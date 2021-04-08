<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaManager;
use App\Traits\Relationable;
use App\Traits\Taggable;
use Carbon\Carbon;

class Place extends Model
{
	use HasFactory;
	use MediaManager;
  use Taggable, Relationable;

	/**
		* The attributes that are mass assignable.
		*
		* @var array
		*/
	protected $fillable = [
		'name',
		'description',
		'author_id'
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

  public $with = ['image', 'tags', 'author'];

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
			return $this->hasMany('App\Models\Project')->whereHas('caldates', function ($query) {
            $query->where('start', '>', Carbon::now()->toDateTimeString());
        });
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

	public function image()
	{
			return $this->morphOne('App\Models\Image', 'imageable');
	}

}
