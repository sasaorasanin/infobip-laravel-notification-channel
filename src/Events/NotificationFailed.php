<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip\Events;

use Illuminate\Notifications\Notification;
use Throwable;

class NotificationFailed
{
    public function __construct(
        public readonly mixed $notifiable,
        public readonly Notification $notification,
        public readonly Throwable $exception,
    ) {}
}
