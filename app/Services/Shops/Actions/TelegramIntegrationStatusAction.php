<?php

declare(strict_types=1);

namespace App\Services\Shops\Actions;

use App\Enums\TelegramSendMessageStatusEnum;
use App\Models\TelegramIntegration;
use App\Repositories\ShopsRepository;
use App\Repositories\TelegramIntegrationsRepository;
use App\Repositories\TelegramSendLogsRepository;
use App\Services\Shops\Http\Requests\TelegramIntegrationStatusRequest;
use FKS\Actions\Action;
use FKS\Search\ValueObjects\SearchConditions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TelegramIntegrationStatusAction extends Action
{
    public function __construct(
        private readonly TelegramIntegrationsRepository $telegramIntegrationsRepository,
        private readonly TelegramSendLogsRepository $sendLogsRepository,
        private readonly ShopsRepository $shopsRepository,
    ) {
    }

    public function handle(int $shopId, SearchConditions $conditions): array
    {
        if (!$this->shopsRepository->findById($shopId)) {
            throw new NotFoundHttpException('Shop not found');
        }

        $integrations = $this->telegramIntegrationsRepository->getQuery()->where('shop_id', $shopId)->get();
        $statisticQuery = $this->sendLogsRepository->getQuery()
            ->selectRaw('count(*) as count, status, chat_id, telegram_integration_id')
            ->where('shop_id', $shopId)
            ->groupBy('status', 'chat_id', 'telegram_integration_id');

        $statistic = $this->sendLogsRepository->search($conditions, $statisticQuery);

        $lastSent = $this->sendLogsRepository->getQuery()
            ->selectRaw('MAX(created_at) as last_sent, telegram_integration_id')
            ->groupBy('telegram_integration_id')
            ->get();

        return $integrations->map(function (TelegramIntegration $integration) use ($statistic, $lastSent) {
            /** @var Collection $integrationStatistic */
            $integrationStatistic = $statistic->get($integration->id);
            return [
                'success_count' => $integrationStatistic->where('status', TelegramSendMessageStatusEnum::SENT),
                'failed_count' => $integrationStatistic->where('status', TelegramSendMessageStatusEnum::FAILED),
                'last_sent' => $lastSent->where('telegram_integration_id', $integration->id)->first()->last_sent,
                'enabled' => $integration->enabled,
                'chat_id' => $integration->chat_id,
            ];
        })->toArray();
    }

    public function asController(int $shopId, TelegramIntegrationStatusRequest $request): JsonResponse
    {
        return response()->json(
            $this->handle($shopId, $request->getSearchConditions()),
        );
    }
}
