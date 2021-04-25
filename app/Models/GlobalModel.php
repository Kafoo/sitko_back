<?php

namespace App\Models;

use App\Jobs\ProcessLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalModel extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        self::created(function($model){
            static::log('Nouvelle création !', $model);
        });

        self::updated(function($model){
            static::log('Nouvelle modification !', $model);
        });

        self::deleted(function($model){
            static::log('Nouvelle suppression !', $model);
        });
    }

    static function log($message, $model){

        $user = auth()->user()->name.' ('.auth()->user()->id.')';
        $model = $model->getMorphClass().' ('.$model->id.')';

        dispatch(new ProcessLog(
            $message.' --- User : '.$user.' / Model : '.$model));
    }

    public function clearNotifications()
    {

      $notifications = Notification::where([
                                ['type', 'App\Notifications\LinkRequest'],
                                ['data->requested_id', $this->id],
                                ['data->requested_type', $this->getMorphClass()],
                            ])
                            ->orWhere([
                                ['type', 'App\Notifications\LinkRequest'],
                                ['data->requesting_id', $this->id],
                                ['data->requesting_type', $this->getMorphClass()],
                            ]);

        $notifications->delete();

    }

}
