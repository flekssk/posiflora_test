<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $guarded = [];

    public function telegramIntegrations(): HasMany|Shop
    {
        return $this->hasMany(TelegramIntegration::class);
    }
}
