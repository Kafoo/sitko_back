<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'public_id',
    	'full',
        'medium',
        'low_medium',
    	'thumb',
    	'deletion',
        'imageable_id',
        'imageable_type'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'imageable_type',
        'imageable_id'
    ];


    public function setDownloading($img)
    {

        $this->full = "downloading" ;
        $this->medium = "downloading" ;
        $this->low_medium = "downloading" ;
        $this->thumb = "downloading" ;
        $this->public_id = "downloading" ;

    }

    public function imageable()
    {
        return $this->morphTo();
    }

}
