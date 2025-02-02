<?php

namespace JobMetric\BanIp;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JobMetric\BanIp\Events\BanIpDeleteEvent;
use JobMetric\BanIp\Events\BanIpStoreEvent;
use JobMetric\BanIp\Events\BanIpUpdateEvent;
use JobMetric\BanIp\Http\Requests\StoreBanIpRequest;
use JobMetric\BanIp\Http\Requests\UpdateBanIpRequest;
use JobMetric\BanIp\Http\Resources\BanIpResource;
use JobMetric\BanIp\Models\BanIp as BanIpModel;
use Spatie\QueryBuilder\QueryBuilder;
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
     * Get the specified ban ip.
     *
     * @param array $filter
     * @return QueryBuilder
     */
    public function query(array $filter = []): QueryBuilder
    {
        $fields = ['id', 'ip', 'type', 'reason', 'banned_at', 'expired_at'];

        return QueryBuilder::for(BanIpModel::class)
            ->allowedFields($fields)
            ->allowedSorts($fields)
            ->allowedFilters($fields)
            ->defaultSort('-id')
            ->where($filter);
    }

    /**
     * Paginate the specified ban ip.
     *
     * @param array $filter
     * @param int $page_limit
     * @return LengthAwarePaginator
     */
    public function paginate(array $filter = [], int $page_limit = 15): LengthAwarePaginator
    {
        return $this->query($filter)->paginate($page_limit);
    }

    /**
     * Get all ban ips.
     *
     * @param array $filter
     * @return Collection
     */
    public function all(array $filter = []): Collection
    {
        return $this->query($filter)->get();
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
            return [
                'ok' => false,
                'message' => trans('package-core::base.validation.errors'),
                'errors' => $validator->errors()->all(),
                'status' => 422
            ];
        } else {
            $data = $validator->validated();
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
                'data' => BanIpResource::make($ban_ip),
                'status' => 201
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
            return [
                'ok' => false,
                'message' => trans('package-core::base.validation.errors'),
                'errors' => $validator->errors()->all(),
                'status' => 422
            ];
        } else {
            $data = $validator->validated();
        }

        return DB::transaction(function () use ($ban_ip_id, $data) {
            /**
             * @var BanIpModel $ban_ip
             */
            $ban_ip = BanIpModel::query()->where('id', $ban_ip_id)->first();

            if (!$ban_ip) {
                return [
                    'ok' => false,
                    'message' => trans('package-core::base.validation.errors'),
                    'errors' => [
                        trans('ban-ip::base.validation.ban_ip_not_found')
                    ],
                    'status' => 401
                ];
            }

            if (array_key_exists('type', $data)) {
                $ban_ip->type = $data['type'];
            }

            if (array_key_exists('reason', $data)) {
                $ban_ip->reason = $data['reason'];
            }

            if (array_key_exists('banned_at', $data)) {
                if ($data['banned_at'] > (array_key_exists('expired_at', $data) ? $data['expired_at'] : $ban_ip->expired_at)) {
                    return [
                        'ok' => false,
                        'message' => trans('package-core::base.validation.errors'),
                        'errors' => [
                            trans('ban-ip::base.validation.banned_at_bigger_expired_at')
                        ],
                        'status' => 422
                    ];
                }

                $ban_ip->banned_at = $data['banned_at'];
            }

            if (array_key_exists('expired_at', $data)) {
                if ($data['expired_at'] < (array_key_exists('banned_at', $data) ? $data['banned_at'] : $ban_ip->banned_at)) {
                    return [
                        'ok' => false,
                        'message' => trans('package-core::base.validation.errors'),
                        'errors' => [
                            trans('ban-ip::base.validation.expired_at_bigger_banned_at')
                        ],
                        'status' => 422
                    ];
                }

                $ban_ip->expired_at = $data['expired_at'];
            }

            $ban_ip->save();

            event(new BanIpUpdateEvent($ban_ip, $data));

            return [
                'ok' => true,
                'message' => trans('ban-ip::base.messages.updated'),
                'data' => BanIpResource::make($ban_ip),
                'status' => 200
            ];
        });
    }

    /**
     * Delete the specified ban ip.
     *
     * @param int $ban_ip_id
     * @return array
     */
    public function delete(int $ban_ip_id): array
    {
        return DB::transaction(function () use ($ban_ip_id) {
            /**
             * @var BanIpModel $ban_ip
             */
            $ban_ip = BanIpModel::query()->where('id', $ban_ip_id)->first();

            if (!$ban_ip) {
                return [
                    'ok' => false,
                    'message' => trans('package-core::base.validation.errors'),
                    'errors' => [
                        trans('ban-ip::base.validation.ban_ip_not_found')
                    ],
                    'status' => 401
                ];
            }

            event(new BanIpDeleteEvent($ban_ip));

            $data = BanIpResource::make($ban_ip);

            $ban_ip->delete();

            return [
                'ok' => true,
                'message' => trans('ban-ip::base.messages.deleted'),
                'data' => $data,
                'status' => 200
            ];
        });
    }

    /**
     * Delete the expired ban ip.
     *
     * @return bool
     */
    public function deleteExpired(): bool
    {
        return DB::transaction(function () {
            $ban_ips = BanIpModel::query()->where('expired_at', '<=', now()->format('Y-m-d H:i:s'))->get();

            if($ban_ips->count() > 0) {
                foreach ($ban_ips as $ban_ip) {
                    /**
                     * @var BanIpModel $ban_ip
                     */
                    $this->delete($ban_ip->id);
                }

                return true;
            }

            return false;
        });
    }
}
