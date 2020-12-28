<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function tagType()
    {
        return $this->belongsTo('App\Models\TagType');
    }

    public function tagable()
    {
        return $this->morphTo();
    }

}
