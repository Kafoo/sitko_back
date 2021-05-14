<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class HostingDuration extends GlobalModel
{
    use HasFactory;

    protected $table = 'hosting_durations';

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
        return trans('appData.hosting_durations.'.$this->name);
    }

}
