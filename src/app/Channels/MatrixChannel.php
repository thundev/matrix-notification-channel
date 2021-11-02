<?php

namespace Thundev\MatrixNotificationChannel\app\Channels;

use Illuminate\Notifications\Notifiable;
use Thundev\MatrixNotificationChannel\app\Notifications\MatrixNotificationInterface;
use Thundev\MatrixNotificationChannel\app\Services\MatrixServiceInterface;

class MatrixChannel
{
    /**
     * @param  Notifiable  $notifiable
     */
    public function send(mixed $notifiable, MatrixNotificationInterface $notification)
    {
        $roomId = $notifiable->routeNotificationFor('matrix', $notification);
        $message = $notification->toMatrix($notifiable);

        app()->get(MatrixServiceInterface::class)->message($message->to($roomId));
    }
}
