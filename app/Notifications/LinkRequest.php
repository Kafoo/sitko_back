<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $requesting;
    protected $requested;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Array $arr)
    {
        $this->requesting = $arr['requesting'];
        $this->requested = $arr['requested'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

    if ($this->requested->getMorphClass() === "user") {
        $message = $this->requesting->name.' veut se connecter à vous.';
    } else if ($this->requested->getMorphClass() === "place") {
        $message = $this->requesting->name.' veut se connecter à "'.$this->requested->name .'"';
    }
    
        return (new MailMessage)
                    ->subject('Nouvelle demande de lien !')
                    ->line($message)
                    ->action('Voir le profil de '.$this->requesting->name, url(config('mail.frontpage_url')."user/".$this->requesting->id));
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {

        return [
            'type' => 'link_request',
            'requesting_id' => $this->requesting->id,
            'requesting_type' => $this->requesting->getMorphClass(),
            'requested_id' => $this->requested->id,
            'requested_type' => $this->requested->getMorphClass(),
            'requested_at' => Carbon::now()->toDateTimeString(),
            'state' => 'pending'
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
