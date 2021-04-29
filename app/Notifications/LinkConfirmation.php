<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LinkConfirmation extends Notification implements ShouldQueue
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

        if ($this->requesting->getMorphClass() === "user") {
            $this->message = '<strong>'.$this->requested->name.'</strong> a accepté votre demande de lien.';
        } else if ($this->requesting->getMorphClass() === "place") {
            $this->message = '<strong>'.$this->requested->name.'</strong> a accepté la demande de lien de "<strong>'.$this->requesting->name .'</strong>"';
        }

        $uri = $this->requested->getMorphClass()."/".$this->requested->id;
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
                    ->subject('Lien confirmé !')
                    ->line(new HtmlString($this->message))
                    ->action('Voir le profil de '.$this->requested->name, $this->external_link);
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
            'type' => 'link_confirmation',
            'requesting_id' => $this->requesting->id,
            'requesting_type' => $this->requesting->getMorphClass(),
            'requested_id' => $this->requested->id,
            'requested_type' => $this->requested->getMorphClass(),
            'confirmed_at' => Carbon::now()->toDateTimeString(),
            'message' => $this->message,
            'external_link' => $this->external_link,
            'vue_link' => $this->vue_link,
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
