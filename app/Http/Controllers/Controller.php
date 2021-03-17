<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function returnOrThrow($e, $message, $info = null){

        if ($this->transactionLevel == 1) {

            if(true){
                DB::rollback();
                return response()->json([
                    'customMessage' => $message,
                    'info' => $info,
                    'more' => $e->getMessage()
                ], 500);
            }else{
                DB::rollback();
                return response()->json([
                    'customMessage' => $message
                ], 500);
            }

        }else{  
            throw new HttpException(500, $e->getMessage());
        }

    }


}
