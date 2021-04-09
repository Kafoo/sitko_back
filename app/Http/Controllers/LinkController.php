<?php
 
namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller {

    private function getLinkNotification($notifiable_id, $notifying_id){
      return Notification::where([
                                ['notifiable_id', $notifiable_id],
                                ['type', 'App\Notifications\LinkRequest'],
                                ['notifiable_type', 'user'],
                                ['data->requesting_id', $notifying_id]
                            ])->orderBy('created_at', 'desc')->first();
    }

    public function request(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not request link';

        $this->beginTransaction();

        try {

            $requested_model = Relation::morphMap()[$request->essence];
            $requested = $requested_model::find($request->id);
            
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

    public function cancel(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not cancel request';

        $this->beginTransaction();

        try {

            $requested_model = Relation::morphMap()[$request->essence];
            $requested = $requested_model::find($request->id);
            
            $requested->getLink(auth()->user())->delete();
            
            if ($requested->getMorphClass() === "place") {
              $notifiable_id = $requested->author->id;
            } else {
              $notifiable_id = $requested->id;
            }
            
            $notifying_id = auth()->user()->id;

            $notification = $this->getLinkNotification($notifiable_id, $notifying_id);

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

    public function unlink(Request $request) 
    {

        //TOTRANSLATE
        $fail_message = 'Could not unlink';

        $this->beginTransaction();

        try {

            $requested_model = Relation::morphMap()[$request->essence];
            $requested = $requested_model::find($request->id);
            
            $requested->getLink(auth()->user())->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e,  $fail_message);
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

        $this->beginTransaction();

        try {

            $requesting_model = Relation::morphMap()[$request->requesting['essence']];
            $requesting = $requesting_model::find($request->requesting['id']);

            $requested_model = Relation::morphMap()[$request->requested['essence']];
            $requested = $requested_model::find($request->requested['id']);
            
            $requested->getLink($requesting)->update(['state' => "confirmed"]);
            
            $notification = $this->getLinkNotification(auth()->user()->id, $requesting->id);
            $notification->timestamps = false;

            $now = Carbon::now()->format('Y-m-d H:i:s.u');

            $notification->update(['data->state' => 'confirmed', 'read_at' => $now, 'updated_at' => $now]);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e,  $fail_message);
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

        $this->beginTransaction();

        try {

            $requesting_model = Relation::morphMap()[$request->requesting['essence']];
            $requesting = $requesting_model::find($request->requesting['id']);

            $requested_model = Relation::morphMap()[$request->requested['essence']];
            $requested = $requested_model::find($request->requested['id']);

            $requested->getLink($requesting)->delete();
            
            $notification = $this->getLinkNotification(auth()->user()->id, $requesting->id);
            $notification->timestamps = false;

            $now = Carbon::now()->format('Y-m-d H:i:s.u');

            $notification->update(['data->state' => 'declined', 'read_at' => $now, 'updated_at' => $now]);

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