<?php

namespace Database\Factories;

use App\Models\TelegramIntegration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TelegramIntegrationFactory extends Factory
{
    protected $model = TelegramIntegration::class;

    public function definition(): array
    {
        return [
            'shop_id' => $this->faker->randomNumber(),
            'bot_token' => Str::random(10),
            'chat_id' => $this->faker->word(),
            'enabled' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
