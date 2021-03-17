<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\MediaManager;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Taggable;
use App\Traits\Projent;

class Event extends Model {
    use HasFactory;
    use MediaManager;
    use Projent;
    use Taggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title',
    	'type',
    	'description',
        'author_id',
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

    public $with = ['tags', 'image', 'caldates', 'place', 'author'];

}
