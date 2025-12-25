<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TelegramSendMessageStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramSendLog extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => TelegramSendMessageStatusEnum::class,
        ];
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(TelegramIntegration::class);
    }
}
