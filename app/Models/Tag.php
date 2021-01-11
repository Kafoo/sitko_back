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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function place()
    {
        return $this->morphedByMany('App\Models\Place', 'taggable')->withTimestamps();;
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Tags_category');
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function user()
    {
        return $this->morphedByMany('App\Models\User', 'taggable')->withTimestamps();;
    }

}
