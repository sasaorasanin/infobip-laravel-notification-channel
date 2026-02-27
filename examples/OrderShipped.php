<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Infobip\InfobipSmsAdvancedMessage;

class OrderShipped extends Notification
{
    public function __construct(
        public readonly string $trackingNumber,
        public readonly string $orderId,
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
    public function toInfobip(mixed $notifiable): InfobipSmsAdvancedMessage
    {
        return (new InfobipSmsAdvancedMessage(
            "Your order #{$this->orderId} has shipped! Track it here: {$this->trackingNumber}"
        ))
            ->from('MyStore')
            ->notifyUrl(route('sms.delivery-callback', ['order' => $this->orderId]));
    }
}
