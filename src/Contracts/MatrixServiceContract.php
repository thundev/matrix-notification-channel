<?php

namespace Thundev\MatrixNotificationChannel\Contracts;

use Thundev\MatrixNotificationChannel\Message\MatrixMessage;

interface MatrixServiceContract
{
    public function message(MatrixMessage $message): bool;
}
