<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LinkRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $requesting;
    protected $requested;
    protected $message;
    protected $external_link;
    protected $vue_link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Array $arr)
    {
        $this->requesting = $arr['requesting'];
        $this->requested = $arr['requested'];

        if ($this->requested->getMorphClass() === "user") {
            $this->message = '<strong>'.$this->requesting->name.'</strong> aimerait se connecter à vous.';
        } else if ($this->requested->getMorphClass() === "place") {
            $this->message = '<strong>'.$this->requesting->name.'</strong> aimerait se connecter à "<strong>'.$this->requested->name .'</strong>"';
        }

        $uri = "user/".$this->requesting->id;
        $this->vue_link = "/".$uri;
        $this->external_link = url(config('mail.frontpage_url').$uri);

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
    
    return (new MailMessage)
                ->subject('Nouvelle demande de lien !')
                ->line(new HtmlString($this->message))
                ->action('Voir le profil de '.$this->requesting->name, $this->external_link);
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
            'message' => $this->message,
            'external_link' => $this->external_link,
            'vue_link' => $this->vue_link,
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
