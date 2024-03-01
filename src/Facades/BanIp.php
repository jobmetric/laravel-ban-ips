<?php

namespace JobMetric\BanIp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JobMetric\BanIp\BanIp
 *
 * @method static array store(array $data)
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
