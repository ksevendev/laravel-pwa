<?php

namespace KsLaravelPwa\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasPushSubscriptions;

    /**
     * Verificar se o usuário tem notificações push ativas.
     */
    public function hasActivePushSubscription()
    {
        return $this->pushSubscriptions()->exists();
    }

}
