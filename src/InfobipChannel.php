<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use NotificationChannels\Infobip\Events\NotificationFailed;
use NotificationChannels\Infobip\Events\NotificationSent;
use NotificationChannels\Infobip\Exceptions\CouldNotSendNotification;

class InfobipChannel
{
    public function __construct(
        protected readonly Infobip $infobip,
        protected readonly Dispatcher $events,
    ) {}

    /**
     * Send the given notification.
     *
     * @throws CouldNotSendNotification
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        $recipient = $this->getRecipient($notifiable);
        
        // Get the message using dynamic method call to avoid IDE warnings
        $message = $notification->{'toInfoBip'}($notifiable);
        
        if (!$message instanceof InfobipMessage) {
            throw CouldNotSendNotification::invalidMessageObject($message);
        }

        try {
            $response = $this->infobip->sendMessage($message, $recipient);
            $sentMessageInfo = $response->getMessages()[0] ?? null;

            $this->events->dispatch(new NotificationSent($notifiable, $notification, $sentMessageInfo));
        } catch (\Exception $exception) {
            $this->events->dispatch(new NotificationFailed($notifiable, $notification, $exception));
        }
    }

    /**
     * Get message recipient.
     *
     * @throws CouldNotSendNotification
     */
    protected function getRecipient(mixed $notifiable): string
    {
        if ($recipient = $notifiable->routeNotificationForInfoBip()) {
            return $recipient;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }
}
