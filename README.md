## Matrix notification channel for Laravel

This package adds support for Matrix notifications to Laravel applications.

### Installation

You can install the package via **composer**:

```bash
composer require thundev/matrix-notification-channel
```

Publish the configurations:

```bash
php artisan vendor:publish --tag="matrix-config"
```

### Usage

#### Routing Matrix notification

To route Matrix notifications to the proper room, define a `routeNotificationForMatrix()` method on your notifiable entity which should return the Matrix room ID to which the notification should be delivered. Make sure to invite your bot into the room first. The bot will automatically accept the invitation upon sending the very first message.

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @param  Notification  $notification
     */
    public function routeNotificationForMatrix($notification): string
    {
        return 'your_room_id';
    }
}
```

#### Creating Notifications

If a notification supports being sent as a Matrix message, your notification should implement `Thundev\MatrixNotificationChannel\app\Notifications\MatrixNotificationInterface` which defines a `toMatrix()` method. This method will receive a `$notifiable` entity and should return an `Thundev\MatrixNotificationChannel\app\Services\MatrixMessage` instance:

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Thundev\MatrixNotificationChannel\app\Notifications\MatrixNotificationInterface;
use Thundev\MatrixNotificationChannel\app\Services\MatrixMessage;

class InvoiceCreatedNotification extends Notification implements MatrixNotificationInterface
{
    use Queueable;
    
    public function via(mixed $notifiable): array
    {
        return ['matrix'];
    }

    public function toMatrix(mixed $notifiable): MatrixMessage
    {
        return (new MatrixMessage())->message('My awesome message!');
    }
}
```

#### Dispatching notification

To dispatch a message for your notifiable instance, simply call the `notify()` method.

```php
(new User())->notify(new InvoiceCreatedNotification());
```
