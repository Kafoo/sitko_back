<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'type',
    	'description',
        'img',
        'img_medium',
        'img_thumb'
    ];

    public function events()
    {
        return $this->morphMany('App\Models\Event', 'child');
    }

}
