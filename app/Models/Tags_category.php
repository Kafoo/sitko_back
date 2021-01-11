<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags_category extends Model
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

	public function tags()
	{
			return $this->hasMany('App\Models\Tag');
	}

}
