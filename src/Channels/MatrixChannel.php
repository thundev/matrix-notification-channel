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
        $matrix = app()->get(MatrixServiceContract::class);

        $to = $notifiable->routeNotificationFor('matrix', $notification);

        if (is_array($to)) {
            foreach ($to as $receiver) {
                $message = $notification
                    ->toMatrix($notifiable)
                    ->to($receiver);
                $matrix->message($message);
            }
        } else {
            $message = $notification
                ->toMatrix($notifiable)
                ->to($to);
            $matrix->message($message);
        }
    }
}
