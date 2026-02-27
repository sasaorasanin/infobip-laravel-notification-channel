# Upgrade Guide

## Upgrading from 1.x to 2.0

### Requirements

- PHP 8.3 or higher (previously 7.2+)
- Laravel 11.x or 12.x (previously 5.5-8.x)
- Infobip API Client 6.2.1 (previously dev-master)

### Authentication Changes

The authentication method has changed from username/password to API key.

**Before (1.x):**
```php
// config/services.php
'infobip' => [
    'username' => env('INFOBIP_USERNAME'),
    'password' => env('INFOBIP_PASSWORD'),
    'from' => env('INFOBIP_FROM'),
],
```

**After (2.0):**
```php
// config/services.php
'infobip' => [
    'api_key' => env('INFOBIP_API_KEY'),
    'base_url' => env('INFOBIP_BASE_URL', 'https://api.infobip.com'),
    'from' => env('INFOBIP_FROM'),
    'notify_url' => env('INFOBIP_NOTIFY_URL', null),
],
```

**Environment Variables:**
```env
# Old
INFOBIP_USERNAME=your-username
INFOBIP_PASSWORD=your-password

# New
INFOBIP_API_KEY=your-api-key-here
INFOBIP_BASE_URL=https://api.infobip.com
```

### Getting Your API Key

1. Log in to your Infobip account
2. Navigate to Settings → API Keys
3. Create a new API key or use an existing one
4. Copy the API key to your `.env` file

### Code Changes

Most notification code remains the same, but type declarations are now enforced:

**Before (1.x):**
```php
class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ["infobip"];
    }

    public function toInfobip($notifiable)
    {
        return (new InfobipMessage)->content("Your account was approved!");
    }
}
```

**After (2.0):**
```php
class AccountApproved extends Notification
{
    public function via($notifiable): array
    {
        return ['infobip'];
    }

    public function toInfobip($notifiable): InfobipMessage
    {
        return (new InfobipMessage('Your account was approved!'));
    }
}
```

### Events

Version 2.0 includes built-in event dispatching:

```php
use NotificationChannels\Infobip\Events\NotificationSent;
use NotificationChannels\Infobip\Events\NotificationFailed;

Event::listen(NotificationSent::class, function ($event) {
    Log::info('SMS sent', [
        'notifiable' => $event->notifiable,
        'message' => $event->sentMessageInfo,
    ]);
});

Event::listen(NotificationFailed::class, function ($event) {
    Log::error('SMS failed', [
        'notifiable' => $event->notifiable,
        'error' => $event->exception->getMessage(),
    ]);
});
```

### Testing

Tests have been migrated from PHPUnit to Pest:

```bash
# Old
composer test

# New (same command, but uses Pest)
composer test
```

### Breaking Changes Summary

1. **PHP Version**: Minimum PHP 8.3 required
2. **Laravel Version**: Laravel 11.x/12.x only
3. **Authentication**: API key instead of username/password
4. **Configuration**: New config structure
5. **Type Safety**: Strict types throughout codebase
6. **Testing**: Pest instead of PHPUnit

### Step-by-Step Migration

1. Update your `composer.json`:
```bash
composer require laravel-notification-channels/infobip:^2.0
```

2. Update your configuration in `config/services.php`

3. Get your API key from Infobip dashboard

4. Update your `.env` file with new variables

5. Test your notifications in a staging environment

6. Deploy to production
