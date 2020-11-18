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


       	$result = $uploadedFileUrl = Cloudinary::upload($request->file, [
                'transformation' => [
                    'gravity' => 'auto',
                    'height' => 100,
                ]]);

       	echo $result->getSecurePath();

/*    	$response = Http::post('https://api.imgbb.com/1/upload', [
    		'key' => '7618071644bd033d9b2f5b22619c5391'
		    'image' => 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'
			]);
    	echo 'yop';
    	echo $response;*/
/*
			# Our new data
			$data = array(
			    'image' => 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'
			);
			# Create a connection
			$url = 'https://api.imgbb.com/1/upload?expiration=600&key=7618071644bd033d9b2f5b22619c5391';
			$ch = curl_init($url);
			# Form data string
			$postString = http_build_query($data, '', '&');
			# Setting our options
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Get the response
			$response = curl_exec($ch);
			curl_close($ch);


			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://www.example.com/");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			$headers = curl_getinfo($ch);

			print "Content-Type: " . $headers['content_type'] . "\n";
			print "response: $result\n";


			var_dump($ch);

			return $response;*/

    }
}