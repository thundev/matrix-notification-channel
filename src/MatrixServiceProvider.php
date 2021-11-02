<?php

namespace Thundev\MatrixNotificationChannel;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Thundev\MatrixNotificationChannel\app\Channels\MatrixChannel;
use Thundev\MatrixNotificationChannel\app\Services\MatrixService;
use Thundev\MatrixNotificationChannel\app\Services\MatrixServiceInterface;
use Thundev\MatrixNotificationChannel\app\Services\MatrixServiceMock;

class MatrixServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MatrixServiceInterface::class, function () {
            return config('matrix.enabled')
                ? new MatrixService(config('matrix.uri'), config('matrix.token'))
                : new MatrixServiceMock();
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('matrix', function () {
                return new MatrixChannel();
            });
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/matrix.php' => config_path('matrix.php'),
        ], 'matrix-config');
    }
}
