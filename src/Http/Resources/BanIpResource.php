<?php

namespace JobMetric\BanIp\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed type
 * @property mixed reason
 * @property mixed banned_at
 * @property mixed expired_at
 */
class BanIpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'reason' => $this->reason,
            'banned_at' => $this->banned_at,
            'expired_at' => $this->expired_at,
        ];
    }
}
