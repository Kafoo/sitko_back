<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
    	'type',
    	'start',
    	'end',
    	'timed',
        'child_id',
        'child_type',
        'place_id'
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
