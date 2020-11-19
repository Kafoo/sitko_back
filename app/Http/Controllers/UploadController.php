<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UploadController extends Controller
{
    public function image(Request $request)
    {

       	$result = $uploadedFileUrl = Cloudinary::upload($request->file);

       	echo $result->getSecurePath();

    }
}