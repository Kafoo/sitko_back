<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
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
        "author_id"
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
