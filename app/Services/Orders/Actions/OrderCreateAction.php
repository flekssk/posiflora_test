<?php

declare(strict_types=1);

namespace App\Services\Orders\Actions;

use App\Models\Order;
use App\Repositories\OrdersRepository;
use App\Repositories\ShopsRepository;
use App\Services\Orders\Actions\Telegram\OrderSendTelegramNotificationAction;
use App\Services\Orders\DTO\OrderCreateDTO;
use App\Services\Orders\Http\Requests\OrderCreateRequest;
use FKS\Actions\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderCreateAction extends Action
{
    public function __construct(
        private readonly ShopsRepository $shopsRepository,
        private readonly OrdersRepository $ordersRepository,
    ) {
    }

    public function handle(int $shopId, OrderCreateDTO $dto): Order
    {
        $shop = $this->shopsRepository->findById($shopId);

        if (!$shop) {
            throw new NotFoundHttpException('Shop not found');
        }

        $order = $this->ordersRepository->create([
            'shop_id' => $shop->id,
            'number' => Str::uuid()->toString(),
            'total' => $dto->total,
            'customer_name' => $dto->customerName,
        ]);

        OrderSendTelegramNotificationAction::run($order);

        return $order;
    }

    public function asController(int $shopId, OrderCreateRequest $request): JsonResponse
    {
        return response()->json(
            $this->handle($shopId, $request->toDTO())->toArray(),
        );
    }
}
