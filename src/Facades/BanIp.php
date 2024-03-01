<?php

namespace JobMetric\BanIp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JobMetric\BanIp\BanIp
 *
 * @method static array store(array $data)
 * @method static array update(int $ban_ip_id, array $data = [])
 * @method static array delete(int $ban_ip_id)
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
