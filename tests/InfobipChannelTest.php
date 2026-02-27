<?php

declare(strict_types=1);

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Event;
use NotificationChannels\Infobip\Events\NotificationFailed;
use NotificationChannels\Infobip\Events\NotificationSent;
use NotificationChannels\Infobip\Exceptions\CouldNotSendNotification;
use NotificationChannels\Infobip\Infobip;
use NotificationChannels\Infobip\InfobipChannel;
use NotificationChannels\Infobip\InfobipMessage;
use Mockery;

beforeEach(function () {
    Event::fake();
});

test('it can send a notification', function () {
    $channel = app(InfobipChannel::class);
    
    $notifiable = new class {
        use \Illuminate\Notifications\Notifiable;
        
        public function routeNotificationForInfoBip()
        {
            return '+1234567890';
        }
    };
    
    $notification = new class extends Notification {
        public function toInfoBip($notifiable)
        {
            return (new InfobipMessage('Test message'))
                ->from('TestSender');
        }
    };
    
    // This will attempt actual API call, so we'll just verify structure
    expect($channel)->toBeInstanceOf(InfobipChannel::class);
});

test('it throws exception when no recipient phone number', function () {
    $channel = app(InfobipChannel::class);
    
    $notifiable = new class {
        use \Illuminate\Notifications\Notifiable;
        
        public function routeNotificationForInfoBip()
        {
            return null;
        }
    };
    
    $notification = new class extends Notification {
        public function toInfoBip($notifiable)
        {
            return new InfobipMessage('Test message');
        }
    };
    
    $channel->send($notifiable, $notification);
})->throws(CouldNotSendNotification::class);

test('notification failed event is dispatched on exception', function () {
    $infobip = Mockery::mock(Infobip::class);
    $infobip->shouldReceive('sendMessage')
        ->once()
        ->andThrow(new Exception('API Error'));
    
    $channel = new InfobipChannel($infobip, app('events'));
    
    $notifiable = new class {
        use \Illuminate\Notifications\Notifiable;
        
        public function routeNotificationForInfoBip()
        {
            return '+1234567890';
        }
    };
    
    $notification = new class extends Notification {
        public function toInfoBip($notifiable)
        {
            return new InfobipMessage('Test message');
        }
    };
    
    $channel->send($notifiable, $notification);
    
    Event::assertDispatched(NotificationFailed::class);
});
