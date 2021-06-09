<?php

namespace App\Jobs;

use App\Models\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $imageModel;
    private $newImage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($imageModel, $newImage)
    {
        $this->imageModel = $imageModel;
        $this->newImage = $newImage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
        
            $cloudinary_response = Cloudinary::upload($this->newImage);

            $this->imageModel->full = $cloudinary_response->getSecurePath();

            $parts = explode('upload/', $this->imageModel->full);

            $this->imageModel->medium = $parts[0].'upload/t_medium/'.$parts[1];
            $this->imageModel->low_medium = $parts[0].'upload/t_low_medium/'.$parts[1];
            $this->imageModel->thumb = $parts[0].'upload/t_thumb/'.$parts[1];
            $this->imageModel->public_id = $cloudinary_response->getPublicId();
            $this->imageModel->save();

        } catch (\Throwable $th) {

            if ($this->imageModel && $this->imageModel->full == "downloading") {
                $this->imageModel->delete();
            }
        }

    }
}
