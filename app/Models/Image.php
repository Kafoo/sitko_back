<?php

namespace App\Models;

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
			if ($this->public_id) {
				Cloudinary::destroy($this->public_id);
			}

			//Store new image
            $this->update($this->cloudinary($newImage));

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

    public function cloudinary($img)
    {

        $cloudinary_response = Cloudinary::upload($img);

        $full = $cloudinary_response->getSecurePath();

        $parts = explode('upload/', $full);

        $medium = $parts[0].'upload/t_medium/'.$parts[1];
        $low_medium = $parts[0].'upload/t_low_medium/'.$parts[1];
        $thumb = $parts[0].'upload/t_thumb/'.$parts[1];
        $public_id = $cloudinary_response->getPublicId();

        return [
            'full' => $full,
            'medium' => $medium,
            'low_medium' => $low_medium,
            'thumb' => $thumb,
            'public_id' => $public_id,
        ];

    }

    public function imageable()
    {
        return $this->morphTo();
    }

}
