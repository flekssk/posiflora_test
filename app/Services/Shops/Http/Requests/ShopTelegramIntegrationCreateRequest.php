<?php

declare(strict_types=1);

namespace App\Services\Shops\Http\Requests;

use App\Services\Shops\DTO\TelegramIntegrationCreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class ShopTelegramIntegrationCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'chat_id'   => ['required', 'string'],
        ];
    }

    public function toDTO(): TelegramIntegrationCreateDTO
    {
        return new TelegramIntegrationCreateDTO(
            $this->input('token'),
            $this->input('chat_id'),
        );
    }
}
