<?php

namespace App\Models;

use DateTime;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

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

    public function getReadAtAttribute($date)
    {
        if ($date) {
            $date = new DateTime($date);
            return $date->format('Y-m-d H:i:s.u');
        }else{
            return null;
        }
    }

}