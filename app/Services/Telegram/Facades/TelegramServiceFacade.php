<?php

declare(strict_types=1);

namespace App\Services\Telegram\Facades;

use App\Services\Telegram\TelegramService;
use Illuminate\Support\Facades\Facade;

class TelegramServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TelegramService::class;
    }
}
