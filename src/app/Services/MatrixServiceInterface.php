<?php

namespace Thundev\MatrixNotificationChannel\app\Services;

interface MatrixServiceInterface
{
    public function message(MatrixMessage $message): bool;
}
