<?php

declare(strict_types=1);

namespace App\Services\Orders\Http\Requests;

use App\Services\Orders\DTO\OrderCreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'total' => 'required|float',
            'customer_name' => 'required|string|max:255',
        ];
    }

    public function toDTO(): OrderCreateDTO
    {
        return new OrderCreateDTO(
            (float) $this->total,
            $this->customer_name
        );
    }
}
