<?php

namespace KsLaravelPwa\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\WebPush\WebPushMessage;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['webpush'];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->message)
            ->action('Ver', url('/'))
            ->icon('/assets/icon.png');
    }
}
