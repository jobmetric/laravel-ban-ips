<?php

namespace JobMetric\BanIp\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use JobMetric\BanIp\Models\BanIp;

class BanIpUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly BanIp $banIp,
        public readonly array $data
    )
    {
    }
}
