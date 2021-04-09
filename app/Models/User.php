<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Traits\MediaManager;
use App\Traits\Notifiable;
use App\Traits\Taggable;
use App\Traits\Relationable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable, HasApiTokens;
    use HasPermissions;
    use MediaManager;
    use Taggable, Relationable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $with = ['image', 'tags'];

	public function place()
	{
        return $this->hasOne('App\Models\Place', 'author_id');
	}

	public function joined_places()
	{
        return $this->belongsToMany('App\Models\Place');
	}

    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
    
}
