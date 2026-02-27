<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip\Exceptions;

use Exception;
use NotificationChannels\Infobip\InfobipMessage;
use NotificationChannels\Infobip\InfobipSmsAdvancedMessage;

class CouldNotSendNotification extends Exception
{
    public static function invalidCredentials(): self
    {
        return new self('Invalid credentials');
    }

    public static function invalidReceiver(): self
    {
        return new self('The notifiable did not have a phone number. Add routeNotificationForInfoBip to your notifiable');
    }

    public static function missingFrom(): self
    {
        return new self('Notification was not sent. Missing `from` number.');
    }

    public static function missingNotifyUrl(): self
    {
        return new self('Notification was not sent. Missing `notify_url`');
    }

    public static function invalidMessageObject(mixed $message): self
    {
        $className = get_class($message) ?: 'Unknown';

        return new self(
            'Notification was not sent. Message object class '.$className.
            ' is invalid. It should be either '.InfobipMessage::class.
            ' or '.InfobipSmsAdvancedMessage::class
        );
    }
}
