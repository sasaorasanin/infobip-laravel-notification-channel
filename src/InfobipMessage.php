<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip;

class InfobipMessage
{
    public ?string $content = null;
    public ?string $from = null;

    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number this message is sent from.
     */
    public function from(string $from): static
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the phone number this message is sent from.
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }
}
