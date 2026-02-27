# Quick Start Guide

## Installation

1. **Install the package:**
```bash
composer require laravel-notification-channels/infobip
```

2. **Configure Infobip in `config/services.php`:**
```php
'infobip' => [
    'api_key' => env('INFOBIP_API_KEY'),
    'base_url' => env('INFOBIP_BASE_URL', 'https://api.infobip.com'),
    'from' => env('INFOBIP_FROM'),
    'notify_url' => env('INFOBIP_NOTIFY_URL', null),
],
```

3. **Add to your `.env` file:**
```env
INFOBIP_API_KEY=your-api-key-here
INFOBIP_FROM=YourSenderName
```

4. **Get your API Key:**
   - Log in to [Infobip Portal](https://portal.infobip.com/)
   - Navigate to Settings → API Keys
   - Create or copy your API key

## Create Your First Notification

1. **Generate a notification:**
```bash
php artisan make:notification WelcomeSms
```

2. **Edit the notification:**
```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Infobip\InfobipMessage;

class WelcomeSms extends Notification
{
    public function via($notifiable): array
    {
        return ['infobip'];
    }

    public function toInfobip($notifiable): InfobipMessage
    {
        return new InfobipMessage(
            "Welcome {$notifiable->name}! Thanks for signing up."
        );
    }
}
```

3. **Add routing to your User model:**
```php
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForInfobip(): ?string
    {
        // Return phone in international format: +1234567890
        return $this->phone_number;
    }
}
```

4. **Send the notification:**
```php
$user = User::find(1);
$user->notify(new WelcomeSms());

// Or use on-demand notifications:
use Illuminate\Support\Facades\Notification;

Notification::route('infobip', '+1234567890')
    ->notify(new WelcomeSms());
```

## Queue Notifications (Recommended)

For better performance, queue your notifications:

```php
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeSms extends Notification implements ShouldQueue
{
    use Queueable;
    
    // ... rest of your notification
}
```

## Testing

Run the tests:
```bash
composer test
```

## That's It!

You're now ready to send SMS notifications through Infobip. Check the [README.md](README.md) for more advanced features and examples.
