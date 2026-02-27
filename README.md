# Laravel Notifications Channel for Infobip

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/infobip.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/infobip)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/infobip.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/infobip)

This package makes it easy to send SMS notifications using [Infobip](https://www.infobip.com/) with Laravel 11.x and 12.x

## Requirements

- PHP 8.3 or higher
- Laravel 11.x or 12.x
- Infobip API Client 6.2.1 or higher

## Contents

- [Installation](#installation)
	- [Setting up the Infobip service](#setting-up-your-infobip-account)
- [Usage](#usage)
	- [On-Demand Notifications](#on-demand-notifications)
    - [Available Message methods](#available-message-methods)
- [Testing](#testing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/infobip
```

## Setting up your Infobip account

Add your Infobip API Key and default sender to your `config/services.php`:

```php
// config/services.php
...
'infobip' => [
    'api_key' => env('INFOBIP_API_KEY'),
    'base_url' => env('INFOBIP_BASE_URL', 'https://api.infobip.com'),
    'from' => env('INFOBIP_FROM', 'Info'),
    'notify_url' => env('INFOBIP_NOTIFY_URL', null),
],
...
```

Add these to your `.env` file:

```env
INFOBIP_API_KEY=your-api-key-here
INFOBIP_FROM=YourSenderName
INFOBIP_BASE_URL=https://api.infobip.com
```

To get your API key, log in to your Infobip account and navigate to Settings > API Keys.

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Infobip\InfobipMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ['infobip'];
    }

    public function toInfobip($notifiable)
    {
        return (new InfobipMessage('Your account was approved!'));
    }
}
```

In your notifiable model, make sure to include a `routeNotificationForInfobip()` method, which returns a phone number:

```php
public function routeNotificationForInfobip()
{
    return $this->phone_number;
}
```

### On-Demand Notifications
Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the `Notification::route` method, you may specify ad-hoc notification routing information before sending the notification:

```php
use Illuminate\Support\Facades\Notification;

Notification::route('infobip', '+1234567890')
    ->notify(new AccountApproved());
```

### Available Message methods

#### `content(string $content)`
Sets the notification message text:

```php
(new InfobipMessage())->content('Your verification code is: 1234');
```

#### `from(string $from)` 
Sets the sender name or number. *Make sure to register the sender name in your Infobip dashboard.*

```php
(new InfobipMessage('Hello'))
    ->from('MyApp');
```

### Advanced Messages

For advanced SMS features like delivery notifications, use `InfobipSmsAdvancedMessage`:

```php
use NotificationChannels\Infobip\InfobipSmsAdvancedMessage;

public function toInfobip($notifiable)
{
    return (new InfobipSmsAdvancedMessage('Your order has shipped!'))
        ->from('MyStore')
        ->notifyUrl('https://yourapp.com/sms/delivery-callback');
}
```

## Events

The channel dispatches two events:

### `NotificationSent`
Fired when a notification is successfully sent.

```php
use NotificationChannels\Infobip\Events\NotificationSent;

Event::listen(NotificationSent::class, function ($event) {
    // $event->notifiable
    // $event->notification
    // $event->sentMessageInfo
});
```

### `NotificationFailed`
Fired when a notification fails to send.

```php
use NotificationChannels\Infobip\Events\NotificationFailed;

Event::listen(NotificationFailed::class, function ($event) {
    // $event->notifiable
    // $event->notification
    // $event->exception
});
```

## Testing

``` bash
composer test
```

## Security

If you discover any security related issues, please use the issue tracker.

## Credits

- [Thomson Maguru](https://github.com/tomsgad)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
