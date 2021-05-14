<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use App\Traits\Imageable;
use App\Traits\Notifiable;
use App\Traits\Taggable;
use App\Traits\Relationable;
use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use stdClass;

class User extends GlobalModel implements 
    MustVerifyEmailContract,
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasFactory, HasRoles, Notifiable, HasApiTokens;
    use HasPermissions;
    use Imageable;
    use Taggable, Relationable;
	use FiltersRecords;
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

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
        'bio',
        'expectations',
        'user_type_id',
        'home_type_id',
        'contact_infos',
        'preferences'
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
        'contact_infos' => 'json',
        'preferences' => 'json',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'contact_infos' => '{}',
        'preferences' => '{"email":{"links":true}}'
    ];

	public function places()
	{
        return $this->hasMany('App\Models\Place', 'author_id');
	}

	public function user_type()
	{
        return $this->hasOne('App\Models\UserType', 'id', 'user_type_id');
	}

	public function home_type()
	{
        return $this->hasOne('App\Models\HomeType', 'id', 'home_type_id');
	}    

	public function joined_places()
	{
        return $this->belongsToMany('App\Models\Place');
	}

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function update(array $attributes = [], array $options = [])
    {

        $arr = $attributes;

        if ($attributes['password']) {
            $arr['password'] = Hash::make($attributes['password']);
        }else{
            unset($arr['password']);
        }

        $response = parent::update($arr, $options);

        $this->updateImage($attributes['image']);

        $this->updateTags($attributes['tags']);

        return $response;
    }

    public function delete()
    {

        $this->deleteImage();

        $this->deleteTags();

        $this->clearRelationships();

        $this->clearNotifications();

        $response = parent::delete();

        return $response;
    }

}
