<?php

declare(strict_types=1);

use NotificationChannels\Infobip\InfobipSmsAdvancedMessage;

test('it extends InfobipMessage', function () {
    $message = new InfobipSmsAdvancedMessage('hello');

    expect($message)->toBeInstanceOf(\NotificationChannels\Infobip\InfobipMessage::class)
        ->and($message->content)->toBe('hello');
});

test('it can set notify url', function () {
    $message = (new InfobipSmsAdvancedMessage())
        ->notifyUrl('https://example.com/callback');

    expect($message->notifyUrl)->toBe('https://example.com/callback');
});

test('it can get notify url', function () {
    $message = (new InfobipSmsAdvancedMessage())
        ->notifyUrl('https://example.com/notify');

    expect($message->getNotifyUrl())->toBe('https://example.com/notify');
});

test('it can chain methods', function () {
    $message = (new InfobipSmsAdvancedMessage())
        ->content('Test message')
        ->from('Sender')
        ->notifyUrl('https://example.com/callback');

    expect($message->content)->toBe('Test message')
        ->and($message->from)->toBe('Sender')
        ->and($message->notifyUrl)->toBe('https://example.com/callback');
});
