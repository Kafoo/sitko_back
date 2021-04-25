<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class Note extends GlobalModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title',
    	'description',
        'place_id',
        'author_id',
        'visibility'
    ];

    public $with = ['place', 'author'];


    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

	public function author()
	{
			return $this->belongsTo('App\Models\User', 'author_id');
	}

}
