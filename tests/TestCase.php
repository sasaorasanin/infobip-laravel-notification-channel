<?php

declare(strict_types=1);

namespace NotificationChannels\Infobip\Test;

use NotificationChannels\Infobip\InfobipServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            InfobipServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('services.infobip', [
            'api_key' => 'test-api-key',
            'base_url' => 'https://api.infobip.com',
            'from' => 'InfoNotify',
            'notify_url' => 'https://example.com/notify',
        ]);
    }
}
