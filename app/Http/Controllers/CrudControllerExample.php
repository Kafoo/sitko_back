<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrudController extends Controller
{

    public function action(Request $request, $entity)
    {

        $fail_message = trans('crud.fail.entity.action');

       DB::beginTransaction();

        # Whatever

        try {       

            //Some stuff

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, $fail_message, trans('moreInfo'));
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.entity.action'),
            'entity' => $entity
        ], 200);
    }

}
