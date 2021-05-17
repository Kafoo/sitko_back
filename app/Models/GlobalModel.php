<?php

namespace App\Models;

use App\Jobs\ProcessLog;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalModel extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($date)
    {
        $date = new DateTime($date);
        return $date->format('Y-m-d H:i:s.u');
    }

    public function getUpdatedAtAttribute($date)
    {
        $date = new DateTime($date);
        return $date->format('Y-m-d H:i:s.u');
    }

    public static function boot()
    {
        parent::boot();

        $arr = [
            'place',
            'event',
            'project',
            'user'
        ];

        self::created(function($model)use($arr){
            if (in_array($model->getMorphClass(), $arr)) {
                static::log('Nouvelle création !', $model);
            }
        });

        self::updated(function($model)use($arr){
            if (in_array($model->getMorphClass(), $arr)) {
                static::log('Nouvelle modification !', $model);
            }
        });

        self::deleted(function($model)use($arr){
            if (in_array($model->getMorphClass(), $arr)) {
                static::log('Nouvelle suppression !', $model);
            }
        });
    }

    static function log($message, $model){
        if (auth()->user()) {
            $user = auth()->user()->name.' ('.auth()->user()->id.')';
            $model = $model->getMorphClass().' ('.$model->id.')';

            dispatch(new ProcessLog(
                $message.' --- User : '.$user.' / Model : '.$model));
        }else{
            dispatch(new ProcessLog(
                'Nouvel utilisateur !'));
        }
    }


    public function clearNotifications()
    {

        $notifications = Notification::where('notifiable_id', $this->id)
                            ->orWhereIn('type', ['App\Notifications\LinkRequest', 'App\Notifications\LinkConfirmation'])
                            ->where([
                                ['data->requested_id', $this->id],
                                ['data->requested_type', $this->getMorphClass()],
                            ])
                            ->orWhere([
                                ['data->requesting_id', $this->id],
                                ['data->requesting_type', $this->getMorphClass()],
                            ]);

        $notifications->delete();

    }

}
