<?php

declare(strict_types=1);

namespace app\Services\Telegram\DTO;

readonly class TelegramSendMessageResultDTO
{
    public function __construct(
        public bool $success,
        public ?string $message = null,
    ) {
    }
}
