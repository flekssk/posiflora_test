<?php

declare(strict_types=1);

namespace App\Services\Shops\Http\Requests;

use FKS\Search\Requests\FilteringDefinitions;
use FKS\Search\Requests\SearchRequest;

class TelegramIntegrationStatusRequest extends SearchRequest
{
    public static function getAvailableFields(): array
    {
        return [
            'id',
            'failed_count',
            'success_count',
        ];
    }

    public static function getFilteringDefinitions(): FilteringDefinitions
    {
        return tap(new FilteringDefinitions(), function (FilteringDefinitions $filteringDefinitions) {
            $filteringDefinitions->dateRange('created_at');
        });
    }

    public static function getSortingDefinitions(): array
    {
        return [];
    }
}
