<?php

declare(strict_types=1);

namespace App\Services\Orders\Actions\Telegram;

use App\Models\Order;
use App\Models\TelegramSendLog;
use FKS\Actions\Action;

class OrderSendTelegramNotificationAction extends Action
{
    public function handle(Order $order): void
    {
        $attempts = TelegramSendLog::query()
            ->where('order_id', $order->id)
            ->get();

        foreach ($order->shop->telegramIntegrations as $integration) {
            $existedAttempts = $attempts->where('telegram_integration_id', $integration->id)->first();

            if ($existedAttempts) {
                $existedAttempts = TelegramSendLog::create([
                    'shop_id' => $order->shop->id,
                    'order_id' => $order->id,
                ]);
            }

            OrderSendTelegramNotificationAttemptAction::run($existedAttempts);
        }
    }
}
