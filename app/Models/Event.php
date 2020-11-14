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
      'child_type'
    ];

		public function type($type){
			$this->type = $type;
		}

    public function child()
    {
        return $this->morphTo();
    }

}
