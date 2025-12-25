<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class TelegramIntegration extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'token' => Attribute::make(
                fn (string $token) => Crypt::decryptString($token),
                fn (string $token) => Crypt::encryptString($token),
            )
        ];
    }

    public function logs(): HasMany|TelegramIntegration
    {
        return $this->hasMany(TelegramSendLog::class);
    }
}
