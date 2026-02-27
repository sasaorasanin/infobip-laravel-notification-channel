<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip;

use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsMessage;
use Infobip\Model\SmsMessageDeliveryReporting;
use Infobip\Model\SmsRequest;
use Infobip\Model\SmsTextContent;
use Infobip\Model\SmsWebhooks;
use NotificationChannels\Infobip\Exceptions\CouldNotSendNotification;

class Infobip
{
    public function __construct(
        public readonly InfobipConfig $config,
    ) {}

    /**
     * Send SMS message to recipient.
     *
     * @throws CouldNotSendNotification
     */
    public function sendMessage(InfobipMessage $message, string $recipient): mixed
    {
        $message->from($this->config->getFrom());

        if ($message instanceof InfobipSmsAdvancedMessage) {
            $message->notifyUrl($this->config->getNotifyUrl());
            return $this->sendSmsAdvanced($message, $recipient);
        }

        return $this->sendSms($message, $recipient);
    }

    /**
     * Send SMS message to recipient.
     */
    protected function sendSms(InfobipMessage $message, string $recipient): mixed
    {
        $client = $this->createSmsApi();

        $destination = new SmsDestination(to: $recipient);
        $content = new SmsTextContent(text: $message->content);

        $smsMessage = new SmsMessage(
            destinations: [$destination],
            content: $content,
            sender: $this->getFrom($message)
        );

        $request = new SmsRequest(messages: [$smsMessage]);

        return $client->sendSmsMessages($request);
    }

    /**
     * Send advanced SMS to recipient.
     */
    protected function sendSmsAdvanced(InfobipSmsAdvancedMessage $message, string $recipient): mixed
    {
        $client = $this->createSmsApi();

        $destination = new SmsDestination(to: $recipient);
        $content = new SmsTextContent(text: $message->content);

        $webhooks = null;
        if ($notifyUrl = $this->getNotifyUrl($message)) {
            $delivery = new SmsMessageDeliveryReporting(url: $notifyUrl);
            $webhooks = new SmsWebhooks(delivery: $delivery);
        }

        $smsMessage = new SmsMessage(
            destinations: [$destination],
            content: $content,
            sender: $this->getFrom($message),
            webhooks: $webhooks
        );

        $request = new SmsRequest(messages: [$smsMessage]);

        return $client->sendSmsMessages($request);
    }

    /**
     * Create SMS API client.
     */
    protected function createSmsApi(): SmsApi
    {
        $configuration = new Configuration(
            host: $this->config->getBaseUrl(),
            apiKey: $this->config->getApiKey()
        );

        return new SmsApi(config: $configuration);
    }

    /**
     * Get message from phone number from message or config.
     *
     * @throws CouldNotSendNotification
     */
    protected function getFrom(InfobipMessage $message): string
    {
        $from = $message->from ?: $this->config->getFrom();

        if (! $from) {
            throw CouldNotSendNotification::missingFrom();
        }

        return $from;
    }

    /**
     * Get SMS notify URL.
     *
     * @throws CouldNotSendNotification
     */
    protected function getNotifyUrl(InfobipSmsAdvancedMessage $message): ?string
    {
        return $message->notifyUrl ?: $this->config->getNotifyUrl();
    }
}
