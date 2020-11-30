<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'type',
    	'start',
    	'end',
    	'timed',
        'child_id',
        'child_type',
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

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

	public function type($type){
		$this->type = $type;
	}

    public function child()
    {
        return $this->morphTo();
    }

}
