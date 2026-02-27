<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip\Events;

use Illuminate\Notifications\Notification;

class NotificationSent
{
    public function __construct(
        public readonly mixed $notifiable,
        public readonly Notification $notification,
        public readonly mixed $sentMessageInfo,
    ) {}
}
