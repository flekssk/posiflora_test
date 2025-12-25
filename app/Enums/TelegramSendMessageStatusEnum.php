<?php

declare(strict_types=1);

namespace App\Enums;

enum TelegramSendMessageStatusEnum: string
{
    case SENT = 'SENT';
    case FAILED = 'FAILED';
}
