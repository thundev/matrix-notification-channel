<?php

namespace Thundev\MatrixNotificationChannel\Contracts;

use Thundev\MatrixNotificationChannel\Message\MatrixMessage;

interface MatrixNotificationContract
{
    public function toMatrix(mixed $notifiable): MatrixMessage;
}
