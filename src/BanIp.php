<?php

namespace JobMetric\BanIp;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JobMetric\BanIp\Events\BanIpStoreEvent;
use JobMetric\BanIp\Events\BanIpUpdateEvent;
use JobMetric\BanIp\Http\Requests\StoreBanIpRequest;
use JobMetric\BanIp\Http\Requests\UpdateBanIpRequest;
use JobMetric\BanIp\Models\BanIp as BanIpModel;
use Throwable;

class BanIp
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Create a new Setting instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Store the specified category.
     *
     * @param array $data
     * @return array
     * @throws Throwable
     */
    public function store(array $data): array
    {
        $validator = Validator::make($data, (new StoreBanIpRequest)->setData($data)->rules());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'ok' => false,
                'message' => trans('ban-ip::base.validation.errors'),
                'errors' => $errors
            ];
        }

        return DB::transaction(function () use ($data) {
            $ban_ip = new BanIpModel;
            $ban_ip->ip = $data['ip'];
            $ban_ip->type = $data['type'];
            $ban_ip->reason = $data['reason'] ?? null;
            $ban_ip->banned_at = $data['banned_at'] ?? now()->format('Y-m-d H:i:s');
            $ban_ip->expired_at = $data['expired_at'] ?? null;
            $ban_ip->save();

            event(new BanIpStoreEvent($ban_ip, $data));

            return [
                'ok' => true,
                'message' => trans('ban-ip::base.messages.created'),
                'data' => $ban_ip
            ];
        });
    }

    /**
     * Update the specified ban ip.
     *
     * @param int $ban_ip_id
     * @param array $data
     * @return array
     */
    public function update(int $ban_ip_id, array $data = []): array
    {
        $validator = Validator::make($data, (new UpdateBanIpRequest)->rules());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'ok' => false,
                'message' => trans('ban-ip::base.validation.errors'),
                'errors' => $errors
            ];
        }

        return DB::transaction(function () use ($ban_ip_id, $data) {
            /**
             * @var BanIpModel $ban_ip
             */
            $ban_ip = BanIpModel::query()->where('id', $ban_ip_id)->first();

            if (!$ban_ip) {
                return [
                    'ok' => false,
                    'message' => trans('ban-ip::base.validation.errors'),
                    'errors' => [
                        trans('ban-ip::base.validation.ban_ip_not_found')
                    ]
                ];
            }

            if (array_key_exists('type', $data)) {
                $ban_ip->type = $data['type'];
            }

            if (array_key_exists('reason', $data)) {
                $ban_ip->reason = $data['reason'];
            }

            if (array_key_exists('banned_at', $data)) {
                if($data['banned_at'] > (array_key_exists('expired_at', $data) ? $data['expired_at'] : $ban_ip->expired_at)) {
                    return [
                        'ok' => false,
                        'message' => trans('ban-ip::base.validation.errors'),
                        'errors' => [
                            trans('ban-ip::base.validation.banned_at_bigger_expired_at')
                        ]
                    ];
                }

                $ban_ip->banned_at = $data['banned_at'];
            }

            if (array_key_exists('expired_at', $data)) {
                $ban_ip->expired_at = $data['expired_at'];
            }

            $ban_ip->save();

            event(new BanIpUpdateEvent($ban_ip, $data));

            return [
                'ok' => true,
                'message' => trans('ban-ip::base.messages.updated'),
                'data' => $ban_ip
            ];
        });
    }
}
