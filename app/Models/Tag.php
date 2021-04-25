<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class Tag extends GlobalModel
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
        'order'
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

    public $with = ['category'];

    public $appends = ['translated_title'];

    public function getTranslatedTitleAttribute()
    {
        if ($this->custom) {
            return $this->title;
        }else{
            return trans('tags.'.$this->title);
        }
    }

    /**
     * Get all of the places that are assigned this tag.
     */
    public function place()
    {
        return $this->morphedByMany('App\Models\Place', 'taggable')->withTimestamps();
    }

    /**
     * Get all of the users that are assigned this tag.
     */
    public function user()
    {
        return $this->morphedByMany('App\Models\Place', 'taggable')->withTimestamps();
    }

    /**
     * Get all of the projects that are assigned this tag.
     */
    public function project()
    {
        return $this->morphedByMany('App\Models\Place', 'taggable')->withTimestamps();
    }

    /**
     * Get all of the events that are assigned this tag.
     */
    public function event()
    {
        return $this->morphedByMany('App\Models\Place', 'taggable')->withTimestamps();
    }

    /**
     * Get all of the events that are assigned this tag.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Tags_category');
    }

}
