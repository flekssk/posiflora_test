<?php

declare(strict_types=1);

namespace App\Services\Orders\Actions\Telegram;

use App\Enums\TelegramSendMessageStatusEnum;
use App\Models\TelegramSendLog;
use App\Services\Telegram\DTO\TelegramSendMessageDTO;
use App\Services\Telegram\Facades\TelegramServiceFacade;
use App\Services\Telegram\ValueObjects\TelegramToken;
use FKS\Actions\Action;

class OrderSendTelegramNotificationAttemptAction extends Action
{
    public function handle(TelegramSendLog $log): void
    {
        if ($log->attempt > config('services.telegram.orders_notifications.max_attempts_count')) {
            return;
        }
        $result = TelegramServiceFacade::sendMessage(
            new TelegramSendMessageDTO(
                (int) $log->integration->chat_id,
                'Has new order'
            ),
            new TelegramToken($log->integration->token)
        );

        $log->update([
            'attempt' => ($existedAttempts ?? 0) + 1,
            'status' => $result->success ? TelegramSendMessageStatusEnum::SENT : TelegramSendMessageStatusEnum::FAILED,
            'sent_at' => now(),
            'message' => $result->message,
        ]);
    }
}
