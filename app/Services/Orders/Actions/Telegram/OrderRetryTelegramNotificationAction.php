<?php

declare(strict_types=1);

namespace App\Services\Orders\Actions\Telegram;

use App\Enums\TelegramSendMessageStatusEnum;
use App\Repositories\TelegramSendLogsRepository;
use FKS\Actions\Action;

class OrderRetryTelegramNotificationAction extends Action
{
    public $commandSignature = 'order:notification_retry';

    public function __construct(private readonly TelegramSendLogsRepository $repository)
    {
    }


    public function handle(): void
    {
        $query = $this->repository->getQuery()
            ->where('status', TelegramSendMessageStatusEnum::FAILED)
            ->where('attempt', '<', config('services.telegram.orders_notifications.max_attempts_count'));

        $query->chunk(
            100,
            function ($attempts) {
                foreach ($attempts as $attempt) {
                    OrderSendTelegramNotificationAttemptAction::run($attempt);
                }
            }
        );
    }
}
