<?php

namespace App\Providers;

use App\Services\Telegram\TelegramApiClient;
use App\Services\ValueObjects\TelegramToken;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(TelegramToken::class, function () {
            return new TelegramApiClient(
                new Client(
                    [
                        'base_uri' => 'https://api.telegram.org/',
                    ]
                )
            );
        });
    }
}
