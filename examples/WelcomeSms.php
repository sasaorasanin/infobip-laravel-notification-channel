<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Infobip\InfobipMessage;

class WelcomeSms extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $userName,
        public readonly string $verificationCode,
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['infobip'];
    }

    /**
     * Get the Infobip SMS representation of the notification.
     */
    public function toInfobip(mixed $notifiable): InfobipMessage
    {
        return (new InfobipMessage(
            "Welcome {$this->userName}! Your verification code is: {$this->verificationCode}"
        ))->from('MyApp');
    }
}
