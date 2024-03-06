<?php

namespace JobMetric\BanIp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JobMetric\BanIp\BanIp
 *
 * @method static \Spatie\QueryBuilder\QueryBuilder query(array $filter = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate(array $filter = [], int $page_limit = 15)
 * @method static \Illuminate\Database\Eloquent\Collection all(array $filter = [])
 * @method static array store(array $data)
 * @method static array update(int $ban_ip_id, array $data = [])
 * @method static bool deleteExpired()
 */
class BanIp extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'BanIp';
    }
}
