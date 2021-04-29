<?php
 
namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller {

    private function getLinkNotification($requesting, $requested){
      return Notification::where([
                                ['type', 'App\Notifications\LinkRequest'],
                                ['data->requesting_id', $requesting->id],
                                ['data->requesting_type', $requesting->getMorphClass()],
                                ['data->requested_id', $requested->id],
                                ['data->requested_type', $requested->getMorphClass()],
                            ])->orderBy('created_at', 'desc')->first();
    }

    public function request(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not request link';

        DB::beginTransaction();

        try {

            $requested_model = Relation::morphMap()[$request->essence];
            $requested = $requested_model::find($request->id);
            
            if ($requested->getLinkState()) {
                throw new \Exception("Error Processing Request", 1);
                
            }else{


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

            return $this->exceptionResponse($e,  $fail_message);
        }

        $arr = [
            'requesting' => auth()->user(),
            'requested' => $requested
        ];
        
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

    public function cancel(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not cancel request';

        DB::beginTransaction();

        try {

            $requesting_model = Relation::morphMap()[$request->requesting['essence']];
            $requesting = $requesting_model::find($request->requesting['id']);

            $requested_model = Relation::morphMap()[$request->requested['essence']];
            $requested = $requested_model::find($request->requested['id']);

            $relation = $requested->getLink($requesting);

            if (!$relation) {
                throw new \Exception("Error Processing Request", 1);
            } else{
                $relation->delete();
            }


            $notification = $this->getLinkNotification($requesting, $requested);

            if ($notification) {
                $notification->delete();
            }

        } catch (\Exception $e) {

            return $this->exceptionResponse($e,  $fail_message);
        }
    
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
        ], 200);

    } 

    public function unlink(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not unlink';

        DB::beginTransaction();

        try {

            $requested_model = Relation::morphMap()[$request->essence];
            $requested = $requested_model::find($request->id);
            
            $requested->getLink(auth()->user())->delete();

        } catch (\Exception $e) {

            return $this->exceptionResponse($e,  $fail_message);
        }
    
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
        ], 200);

    }

    public function confirm(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not confirm link';

        DB::beginTransaction();

        try {

            $requesting_model = Relation::morphMap()[$request->requesting['essence']];
            $requesting = $requesting_model::find($request->requesting['id']);

            $requested_model = Relation::morphMap()[$request->requested['essence']];
            $requested = $requested_model::find($request->requested['id']);
            
            $requested->getLink($requesting)->update(['state' => "confirmed"]);
            
            $notification = $this->getLinkNotification($requesting, $requested);
            $notification->timestamps = false;

            $now = Carbon::now()->format('Y-m-d H:i:s.u');

            $notification->update(['data->state' => 'confirmed', 'read_at' => $now, 'updated_at' => $now]);

        } catch (\Exception $e) {

            return $this->exceptionResponse($e,  $fail_message);
        }
    
        $arr = [
            'requested' => $requested,
            'requesting' => $requesting
        ];

        if ($requesting->getMorphClass() === "place") {
            $requesting->author->notify(new \App\Notifications\LinkConfirmation($arr));
        }else{
            $requesting->notify(new \App\Notifications\LinkConfirmation($arr));
        }

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
            'notification' => new NotificationResource($notification)
        ], 200);

    }

    public function decline(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not decline link';

        DB::beginTransaction();

        try {

            $requesting_model = Relation::morphMap()[$request->requesting['essence']];
            $requesting = $requesting_model::find($request->requesting['id']);

            $requested_model = Relation::morphMap()[$request->requested['essence']];
            $requested = $requested_model::find($request->requested['id']);

            $requested->getLink($requesting)->delete();
            
            $notification = $this->getLinkNotification($requesting, $requested);
            $notification->timestamps = false;

            $now = Carbon::now()->format('Y-m-d H:i:s.u');

            $notification->update(['data->state' => 'declined', 'read_at' => $now, 'updated_at' => $now]);

        } catch (\Exception $e) {

            return $this->exceptionResponse($e,  $fail_message);
        }
    
        DB::commit();

        return response()->json([
            'success' => trans('crud.success.link.update'),
            'notification' => new NotificationResource($notification)
        ], 200);

    }


}