<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class Notification extends DatabaseNotification
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

}