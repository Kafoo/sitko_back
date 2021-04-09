<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Notifications\Notifiable as BaseNotifiable;

trait Notifiable
{
    use BaseNotifiable;

    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {

        $notifications = $this->morphMany(Notification::class, 'notifiable')
                            ->orderBy('created_at', 'desc');

        return $notifications;
    }
}