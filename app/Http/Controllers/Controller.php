<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function exceptionResponse($e, $message){

        DB::rollback();

        if ($e instanceof CustomException) {
            $info = $e->getMessage();
        }else if ($e instanceof ValidationException){
            throw $e;
        }else{
            if(config('app.debug')){

                return response()->json([
                    'customMessage' => $message,
                    'info' => '',
                    'more' => $e->getMessage()
                ], 500);    
            }
            $info = '';
        }
      
        return response()->json([
            'customMessage' => $message,
            'info' => $info
        ], 500);


    }

    protected function visibilityFilter($items){
        return $items->filter(function($item) {
            return Gate::allows('view', $item);;
        });
    }

}
