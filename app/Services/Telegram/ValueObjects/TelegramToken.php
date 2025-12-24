<?php

declare(strict_types=1);

namespace App\Services\Telegram\ValueObjects;

use Stringable;

readonly class TelegramToken implements Stringable
{
    public function __construct(public string $token)
    {
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
