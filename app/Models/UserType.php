<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class UserType extends GlobalModel
{
    use HasFactory;

    protected $table = 'user_types';

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
        return trans('appData.user_types.'.$this->name);
    }

}
