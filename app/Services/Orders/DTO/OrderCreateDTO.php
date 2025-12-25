<?php

declare(strict_types=1);

namespace App\Services\Orders\DTO;

readonly class OrderCreateDTO
{
    public function __construct(
        public float $total,
        public string $customerName,
    ) {
    }
}
