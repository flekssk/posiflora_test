<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use app\Services\Telegram\DTO\TelegramSendMessageDTO;
use app\Services\Telegram\DTO\TelegramSendMessageResultDTO;
use App\Services\Telegram\ValueObjects\TelegramToken;

readonly class TelegramService
{
    public function __construct(private TelegramApiClient $telegramApiClient) {}

    public function sendMessage(
        TelegramSendMessageDTO $dto,
        TelegramToken $token
    ): TelegramSendMessageResultDTO {
        try {
            $this->telegramApiClient->sendMessage($dto, $token);
        } catch (\Throwable $exception) {
            return new TelegramSendMessageResultDTO(false, $exception->getMessage());
        }

        return new TelegramSendMessageResultDTO(true);
    }
}
