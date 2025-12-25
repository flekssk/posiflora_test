<?php

declare(strict_types=1);

namespace App\Services\Shops\DTO;

class TelegramIntegrationCreateDTO
{
    public function __construct(
        public string $token,
        public int $chatId,
    ) {
    }
}
