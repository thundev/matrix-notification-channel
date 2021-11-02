<?php

namespace Thundev\MatrixNotificationChannel\app\Services;

class MatrixServiceMock implements MatrixServiceInterface
{
    public function message(MatrixMessage $message): bool
    {
        return true;
    }
}
