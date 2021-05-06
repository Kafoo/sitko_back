<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class Visibility extends GlobalModel
{
    use HasFactory;

    protected $table = 'visibilities';

    public $appends = ['translated_name'];

    public function getTranslatedNameAttribute()
    {
        return trans('appData.visibilities.'.$this->name);
    }

}
