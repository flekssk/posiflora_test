<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use FKS\Repositories\Repository;
use FKS\Search\Repositories\SearchRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Repository<Order>
 */
class OrdersRepository extends SearchRepository
{
    public static function getEntityInstance(): Model
    {
        return new Order();
    }
}
