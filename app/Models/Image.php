<?php

namespace App\Models;

use App\Jobs\UploadImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    public function change($newImage){

		// If we have a string (Blob)
		if (gettype($newImage) === "string" ) {

			//Delete old image
			if ($this->public_id && $this->public_id != "downloading") {
				Cloudinary::destroy($this->public_id);
			}

			//Upload and store new image
            $this->upload($newImage);
            $this->save();

		//Else, generic image or same image
		}else{
			
			if ($this->full !== $newImage['full']) {

				if ($this->public_id) {
					Cloudinary::destroy($this->public_id);
				}

				 $this->update($newImage);
			}else{
				// Same images, do nothing
			}
		}
    }


    public function upload($img)
    {

        dispatch(new UploadImage($this, $img));

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
