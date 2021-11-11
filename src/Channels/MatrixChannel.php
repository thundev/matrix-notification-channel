<?php

namespace Thundev\MatrixNotificationChannel\Channels;

use Illuminate\Notifications\Notifiable;
use Thundev\MatrixNotificationChannel\Contracts\MatrixNotificationContract;
use Thundev\MatrixNotificationChannel\Contracts\MatrixServiceContract;

class MatrixChannel
{
    /**
     * @param  Notifiable  $notifiable
     */
    public function send(mixed $notifiable, MatrixNotificationContract $notification)
    {
        $message = $notification
            ->toMatrix($notifiable)
            ->to($notifiable->routeNotificationFor('matrix', $notification));

        app()->get(MatrixServiceContract::class)->message($message);
    }
}
