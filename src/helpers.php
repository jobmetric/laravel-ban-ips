<?php

use JobMetric\BanIp\BanIp;
use JobMetric\BanIp\Facades\BanIp as BanIpFacade;

if (!function_exists('storeBanIp')) {
    /**
     * Get the BanIp instance.
     *
     * @param array $data
     *
     * @return array
     */
    function storeBanIp(array $data): array
    {
        return BanIpFacade::store($data);
    }
}

if (!function_exists('updateBanIp')) {
    /**
     * Get the BanIp instance.
     *
     * @param int $ban_ip_id
     * @param array $data
     *
     * @return array
     */
    function updateBanIp(int $ban_ip_id, array $data = []): array
    {
        return BanIpFacade::update($ban_ip_id, $data);
    }
}

if (!function_exists('deleteExpiredBanIp')) {
    /**
     * Get the BanIp instance.
     *
     * @return bool
     */
    function deleteExpiredBanIp(): bool
    {
        return BanIpFacade::deleteExpired();
    }
}
