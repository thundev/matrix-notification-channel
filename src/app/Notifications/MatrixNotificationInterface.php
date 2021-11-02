<?php

namespace Thundev\MatrixNotificationChannel\app\Notifications;

use Thundev\MatrixNotificationChannel\app\Services\MatrixMessage;

interface MatrixNotificationInterface
{
    public function toMatrix(mixed $notifiable): MatrixMessage;
}
