<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SampleTwo extends Notification
{
    use Queueable;

    private $title = 'Sample Two';
    
    private $body;
    
    private $clickAction;

    private $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $sender, string $body, string $clickAction)
    {
        $this->sender = $sender;
        $this->body = $body;
        $this->clickAction = $clickAction;
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
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title'         => $this->title,
            'body'          => $this->body,
            'click_action'  => $this->clickAction,
            'sender'        => [
                'name'  => $this->sender->name,
                'photo'  => $this->sender->photo,
            ]
        ];
    }
}
