<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Traits\MediaManager;

class Project extends Model
{
    use HasFactory;
    use MediaManager;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title',
    	'type',
    	'description',
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

    public function events()
    {
        return $this->morphMany('App\Models\Event', 'child');
    }

    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }


    public function storeEvents($events){

        $newEvents = [];

        foreach ($events as $event) {
            $eventModel = new Event($event);
            $eventModel->type('project');
            $newEvents[] = $eventModel;
        }

        $this->events = $this->events()->saveMany($newEvents);
    }

}
