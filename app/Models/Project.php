<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'type',
    	'description',
        'place_id'
    ];


    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function events()
    {
        return $this->morphMany('App\Models\Event', 'child');
    }

    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }

}
