<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Shop;
use FKS\Repositories\Repository;
use FKS\Search\Repositories\SearchRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Repository<Shop>
 */
class ShopsRepository extends SearchRepository
{
    public static function getEntityInstance(): Model
    {
        return new Shop();
    }
}
