<?php

namespace KsLaravelPwa\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class UserNotification extends Notification
{
    use Queueable;

    private $title;
    private $body;
    private $url;

    public function __construct($title, $body, $url = '/')
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        //return ['webPush'];
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->action('Ver agora', $this->url)
            ->icon('/assets/images/96x96.png');
    }
}
