<?php

namespace App\Providers;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Telegram\Telegram;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Telegram\TelegramChannel;

class TelegramNotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Telegram::class, static fn ($app) => new Telegram(
            $app['config']['services.telegram-bot-api.token'],
            $app->make(HttpClient::class),
        ));

        Notification::resolved(static function (ChannelManager $service) {
            $service->extend('telegram', static fn ($app) => $app->make(TelegramChannel::class));
        });
    }
}