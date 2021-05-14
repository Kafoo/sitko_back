<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Welcome extends Notification
{
    use Queueable;

    private $message = '<strong>Bienvenue sur Sitko !</strong> Tu peux maintenant <strong>éditer ton profil</strong>, créer un lieu ou consulter la carte !';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        $this->notifiable = $notifiable;
        return [
            'type' => 'welcome',
            'message' => $this->message,
            'vue_link' => '/profil',
            'image' => 'logo'
        ];
    }
}
