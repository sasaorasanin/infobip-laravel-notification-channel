<?php

declare(strict_types=1);

use NotificationChannels\Infobip\InfobipConfig;

test('it can get api key from config', function () {
    $config = new InfobipConfig([
        'api_key' => 'test-key-123',
    ]);

    expect($config->getApiKey())->toBe('test-key-123');
});

test('it can get base url from config', function () {
    $config = new InfobipConfig([
        'base_url' => 'https://custom.infobip.com',
    ]);

    expect($config->getBaseUrl())->toBe('https://custom.infobip.com');
});

test('it uses default base url when not provided', function () {
    $config = new InfobipConfig([]);

    expect($config->getBaseUrl())->toBe('https://api.infobip.com');
});

test('it can get from number', function () {
    $config = new InfobipConfig([
        'from' => 'InfoNotify',
    ]);

    expect($config->getFrom())->toBe('InfoNotify');
});

test('it can get notify url', function () {
    $config = new InfobipConfig([
        'notify_url' => 'https://example.com/callback',
    ]);

    expect($config->getNotifyUrl())->toBe('https://example.com/callback');
});

test('it returns null for missing optional config', function () {
    $config = new InfobipConfig([]);

    expect($config->getFrom())->toBeNull()
        ->and($config->getNotifyUrl())->toBeNull();
});
