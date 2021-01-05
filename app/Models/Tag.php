<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title',
    	'custom',
    ];

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function place()
    {
        return $this->morphedByMany('App\Models\Place', 'taggable')->withTimestamps();;
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function user()
    {
        return $this->morphedByMany('App\Models\User', 'taggable')->withTimestamps();;
    }

}
