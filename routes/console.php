<?php

use App\Services\Orders\Actions\Telegram\OrderRetryTelegramNotificationAction;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Facades\Actions;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Actions::registerCommandsForAction(OrderRetryTelegramNotificationAction::class);

\Illuminate\Support\Facades\Schedule::command('order:notification_retry')->everyFiveMinutes();
