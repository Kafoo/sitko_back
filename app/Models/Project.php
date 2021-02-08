<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaManager;


class Project extends Model
{
    use HasFactory;
    use MediaManager;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title',
    	'type',
    	'description',
        'place_id'
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

    public $with = ['tags', 'image', 'caldates', 'place'];

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function caldates()
    {
        return $this->morphMany('App\Models\Caldate', 'child');
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
