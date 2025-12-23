<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
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
}
