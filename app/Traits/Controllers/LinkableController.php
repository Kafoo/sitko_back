<?php
 
namespace App\Traits\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait LinkableController {

    private function getLinkNotification($notifiable_id){
      return Notification::where([
                                ['notifiable_id', $notifiable_id],
                                ['type', 'App\Notifications\LinkRequest'],
                                ['notifiable_type', 'user'],
                                ['data','LIKE','%"requesting_id":"'.auth()->user()->id.'"%']
                            ])->orderBy('created_at', 'desc')->first();
    }

    public function requestLink($requested_id) 
    {

        $requested = $this->model::find($requested_id);

        //TOTRANSLATE
        $fail_message = 'Could not request link';

        $this->beginTransaction();

        try {
            
            if ($requested->getLinkState()) {
                throw new \Exception("Error Processing Request", 1);
                
            }else{

                $arr = [
                    'requesting' => auth()->user(),
                    'requested' => $requested
                ];

                $relation = new \App\Models\Relationship([
                                'first_id' => auth()->user()->id,
                                'first_type' => 'user',
                                'second_id' => $requested->id,
                                'second_type' => $requested->getMorphClass(),
                                'state' => 'pending'
                                ]);

                $relation->save();



            }


        } catch (\Exception $e) {

            return $this->returnOrThrow($e,  $fail_message);
        }
        
        if ($requested->getMorphClass() === "place") {
            $requested->author->notify(new \App\Notifications\LinkRequest($arr));
        }else{
            $requested->notify(new \App\Notifications\LinkRequest($arr));
        }

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),

        ], 200);

    }

    public function cancelLink($requested_id) 
    {

        $requested = $this->model::find($requested_id);

        //TOTRANSLATE
        $fail_message = 'Could not cancel request';

        $this->beginTransaction();

        try {
            
            $requested->getLink(auth()->user())->delete();
            
            if ($requested->getMorphClass() === "place") {
              $notifiable_id = $requested->author->id;
            } else {
              $notifiable_id = $requested->id;
            }
            
            $notification = $this->getLinkNotification($notifiable_id);

            if ($notification) {
                $notification->delete();
            }

        } catch (\Exception $e) {

            return $this->returnOrThrow($e,  $fail_message);
        }
    
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
        ], 200);

    } 

    public function unlink($requested_id) 
    {

        $requested = $this->model::find($requested_id);

        //TOTRANSLATE
        $fail_message = 'Could not unlink';

        $this->beginTransaction();

        try {
            
            $requested->getLink(auth()->user())->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e,  $fail_message);
        }
    
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
        ], 200);

    }

    public function confirmLink($requested_id) 
    {

        $requested = $this->model::find($requested_id);

        //TOTRANSLATE
        $fail_message = 'Could not confirm link';

        $this->beginTransaction();

        try {
            
            $requested->getLink(auth()->user())->update(['state' => "confirmed"]);

            if ($requested->getMorphClass() === "place") {
              $notifiable_id = $requested->author->id;
            } else {
              $notifiable_id = $requested->id;
            }
            
            $notification = $this->getLinkNotification($notifiable_id);
            $notification->timestamps = false;

            $now = Carbon::now()->format('Y-m-d H:i:s.u');

            $notification->update(['data->state' => 'confirmed', 'updated_at' => $now]);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e,  $fail_message);
        }
    
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
            'notification' => new NotificationResource($notification)
        ], 200);

    }

    public function declineLink($requested_id) 
    {

        $requested = $this->model::find($requested_id);

        //TOTRANSLATE
        $fail_message = 'Could not decline link';

        $this->beginTransaction();

        try {

            $requested->getLink(auth()->user())->delete();

            if ($requested->getMorphClass() === "place") {
              $notifiable_id = $requested->author->id;
            } else {
              $notifiable_id = $requested->id;
            }
            
            $notification = $this->getLinkNotification($notifiable_id);

            $notification->update(['data->state' => 'declined']);


        } catch (\Exception $e) {

            return $this->returnOrThrow($e,  $fail_message);
        }
    
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
            'notification' => new NotificationResource($notification)
        ], 200);

    }


}