<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\TelegramSendLog;
use FKS\Repositories\Repository;
use FKS\Search\Repositories\SearchRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Repository<TelegramSendLog>
 */
class TelegramSendLogsRepository extends SearchRepository
{
    public static function getEntityInstance(): Model
    {
        return new TelegramSendLog();
    }
}
