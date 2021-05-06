<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class Tags_category extends GlobalModel
{
    use HasFactory;

    protected $table = 'tags_categories';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public $appends = ['translated_name'];

    public function getTranslatedNameAttribute()
    {
        return trans('appData.tags.categories.'.$this->name);
    }

	public function tags()
	{
			return $this->hasMany('App\Models\Tag');
	}

}
