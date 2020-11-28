<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;


	public function projects()
	{
	    return $this->hasMany('App\Models\Project');
	}

	public function events()
	{
	    return $this->hasMany('App\Models\Event');
	}

	public function image()
	{
	    return $this->morphOne('App\Models\Image', 'imageable');
	}

}
