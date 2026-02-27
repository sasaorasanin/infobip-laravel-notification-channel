<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip;

class InfobipSmsAdvancedMessage extends InfobipMessage
{
    public ?string $notifyUrl = null;

    public function notifyUrl(?string $notifyUrl): static
    {
        $this->notifyUrl = $notifyUrl;

        return $this;
    }

    public function getNotifyUrl(): ?string
    {
        return $this->notifyUrl;
    }
}
