<?php

namespace App\Notifications;

use App\Mail\GlobalMail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LinkConfirmation extends LinkNotification implements ShouldQueue
{
    use Queueable;

    protected $requesting;
    protected $requested;
    protected $notifiable;

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

    private function getMessage()
    {
        if ($this->requesting->getMorphClass() === "user") {
            return trans('emails.link_confirmation.toUser', 
                        ['requested' => $this->requested->name]
                        , $this->notifiable->locale);
        } else if ($this->requesting->getMorphClass() === "place") {
            return trans('emails.link_confirmation.toPlace', 
                        ['requesting' => $this->requesting->name,
                        'requested' => $this->requested->name],
                        $this->notifiable->locale);;
        }
    }

    private function getVueLink()
    {
        return "/".$this->requested->getMorphClass()."/".$this->requested->id;
    }

    private function getExternalLink()
    {
        return url(config('mail.frontpage_url').$this->requested->getMorphClass()."/".$this->requested->id);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->notifiable = $notifiable;

        $requested_type = $this->requested->getMorphClass();

        $url_text = trans('emails.goto.'.$requested_type,
                        [$requested_type => $this->requested->name], 
                        $notifiable->locale);
        
        return $this->getMail([
            'subject' => trans('emails.link_confirmation.subject', [], $notifiable->locale),
            'content' => new HtmlString($this->getMessage()),
            'url' => $this->getExternalLink(),
            'url_text' => $url_text,
            'locale' => $notifiable->locale
        ]);

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
            'type' => 'link_confirmation',
            'requesting_id' => $this->requesting->id,
            'requesting_type' => $this->requesting->getMorphClass(),
            'requested_id' => $this->requested->id,
            'requested_type' => $this->requested->getMorphClass(),
            'confirmed_at' => Carbon::now()->toDateTimeString(),
            'message' => $this->getMessage(),
            'external_link' => $this->getExternalLink(),
            'vue_link' => $this->getVueLink(),
        ];
    }

}
