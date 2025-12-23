<?php

declare(strict_types=1);

namespace App\Services\Telegram\DTO;

readonly class TelegramSendMessageDTO
{
    public function __construct(
        public int $chatId,
        public string $text,
        public ?array $keyboard = null,
        public bool $markdown = false,
    ) {
    }
}
