<?php

declare(strict_types=1);

namespace App\Services\Shops\Actions;

use App\Models\Shop;
use App\Models\TelegramIntegration;
use App\Repositories\ShopsRepository;
use App\Repositories\TelegramIntegrationsRepository;
use App\Services\Shops\DTO\TelegramIntegrationCreateDTO;
use App\Services\Shops\Http\Requests\ShopTelegramIntegrationCreateRequest;
use FKS\Actions\Action;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TelegramIntegrationStatusAction extends Action
{
    public function __construct(
        private readonly TelegramIntegrationsRepository $repository,
        private readonly ShopsRepository $shopsRepository,
    ) {
    }

    public function handle(int $shopId): array
    {
        if (!$this->shopsRepository->findById($shopId)) {
            throw new NotFoundHttpException('Shop not found');
        }

        $integrations = $this->repository->getQuery()->where('shop_id', $shopId)->get();

        return $integrations->map(function (TelegramIntegration $integration) {
            return [

            ];
        });
    }

    public function asController(int $shopId): Response
    {
        $this->handle($shopId);

        return response()->noContent();
    }
}
