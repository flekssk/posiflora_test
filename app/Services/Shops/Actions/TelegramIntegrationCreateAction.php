<?php

declare(strict_types=1);

namespace App\Services\Shops\Actions;

use App\Models\Shop;
use App\Repositories\TelegramIntegrationsRepository;
use App\Services\Shops\DTO\TelegramIntegrationCreateDTO;
use App\Services\Shops\Http\Requests\ShopTelegramIntegrationCreateRequest;
use FKS\Actions\Action;
use Illuminate\Http\Response;

class TelegramIntegrationCreateAction extends Action
{
    public function __construct(private readonly TelegramIntegrationsRepository $repository)
    {
    }

    public function handle(Shop $shop, TelegramIntegrationCreateDTO $dto): void
    {
        $this->repository->create([
            'shop_id' => $shop->id,
            'token' => $dto->token,
            'chat_id' => $dto->chatId,
        ]);
    }

    public function asController(int $shopId, ShopTelegramIntegrationCreateRequest $request): Response
    {
        $this->handle(Shop::findOrFail($shopId), $request->toDTO());

        return response()->noContent();
    }
}
