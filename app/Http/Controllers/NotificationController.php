<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $chunk = null)
    {
    
        if ($chunk) {
            return NotificationResource::collection(auth()->user()->notifications->take($chunk));
        }else{
            return NotificationResource::collection(auth()->user()->notifications);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param mixed $projent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $projent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {

        $fail_message = trans('crud.fail.notification.deletion');

        DB::beginTransaction();

        # Delete notification

        try {       

            if ($notification->data['state'] !== "pending") {
                $notification->delete();
            }else{
                throw new \Exception("Error Processing Request", 1);
                
            }

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, $fail_message);
        }

        # Success
        
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.notification.deletion')
        ], 200);
    }

    /**
     * Read the notification
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Notification $notification
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request, Notification $notification)
    {

        $fail_message = trans('crud.fail.notification.deletion');

        DB::beginTransaction();

        # Delete notification

        try {       

            $notification->markAsRead();


        } catch (\Exception $e) {

            return response()->json([
            'message' => 'not able to read',
            'no_alert' => true
        ], 500);
        }

        # Success
        
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.notification.update'),
            'notification' => new NotificationResource($notification)
        ], 200);
    }

}
