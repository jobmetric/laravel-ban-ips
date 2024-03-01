<?php

namespace JobMetric\BanIp;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JobMetric\BanIp\Events\BanIpStoreEvent;
use JobMetric\BanIp\Http\Requests\StoreBanIpRequest;
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
            $ban_ip->banned_at = $data['banned_at'] ?? null;
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
}
