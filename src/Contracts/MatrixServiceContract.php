<?php

namespace Thundev\MatrixNotificationChannel\Contracts;

use Thundev\MatrixNotificationChannel\Message\MatrixMessage;

interface MatrixServiceContract
{
    public function message(MatrixMessage $message): bool;
    public function joinRoom(string $roomId): bool;
    public function createRoom(string $roomAlias = ''): ?string;
    public function leaveRoom(string $roomId): bool;
    public function invite(string $username, string $roomId): bool;
}
