<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Route notifications for the Infobip channel.
     */
    public function routeNotificationForInfobip(): ?string
    {
        // Return the user's phone number
        // Make sure it's in international format with country code
        // Example: +1234567890
        return $this->phone_number;
    }
}
