<?php

namespace Thundev\MatrixNotificationChannel\Service;

use Thundev\MatrixNotificationChannel\Contracts\MatrixServiceContract;
use Thundev\MatrixNotificationChannel\Message\MatrixMessage;

class MatrixServiceMock implements MatrixServiceContract
{
    public function message(MatrixMessage $message): bool
    {
        return true;
    }
}
