<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class Relationship extends GlobalModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'first_id',
    	'first_type',
    	'second_id',
        'second_type',
        'state'
    ];
}
