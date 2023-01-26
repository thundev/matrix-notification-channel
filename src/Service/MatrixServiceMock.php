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

    public function joinRoom(string $roomId): bool
    {
        return true;
    }

    public function createRoom(string $roomAlias = ''): ?string
    {
        return 'new_room';
    }

    public function leaveRoom(string $roomId): bool
    {
        return true;
    }

    public function invite(string $username, string $roomId): bool
    {
        return true;
    }
}
