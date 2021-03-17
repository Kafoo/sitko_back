<?php

namespace App\Traits;

trait Projent
{

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public function caldates()
    {
        return $this->morphMany('App\Models\Caldate', 'child');
    }

    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }

}
