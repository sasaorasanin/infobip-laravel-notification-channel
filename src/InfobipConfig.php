<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip;

class InfobipConfig
{
    public function __construct(
        protected readonly array $config
    ) {}

    public function getApiKey(): string
    {
        return $this->config['api_key'] ?? '';
    }

    public function getBaseUrl(): string
    {
        return $this->config['base_url'] ?? 'https://api.infobip.com';
    }

    public function getFrom(): ?string
    {
        return $this->config['from'] ?? null;
    }

    public function getNotifyUrl(): ?string
    {
        return $this->config['notify_url'] ?? null;
    }
}
