<?php

namespace JobMetric\BanIp\Commands;

use Illuminate\Console\Command;
use JobMetric\BanIp\Facades\BanIp;
use JobMetric\PackageCore\Commands\ConsoleTools;

class BanIpRemove extends Command
{
    use ConsoleTools;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ban-ip:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove ban ip expire time';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hasBanIp = BanIp::deleteExpired();

        if($hasBanIp) {
            $this->message('Ban IP has been <options=bold>removed</> successfully.', 'success');
            return 0;
        }

        $this->message('No Ban IP found.', 'error');
        return 1;

    }
}
