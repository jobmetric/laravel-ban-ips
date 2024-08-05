<?php

namespace JobMetric\BanIp;

use JobMetric\BanIp\Models\BanIp as BanIpModels;
use JobMetric\PackageCore\Exceptions\ConsoleKernelFileNotFoundException;
use JobMetric\PackageCore\Exceptions\MigrationFolderNotFoundException;
use JobMetric\PackageCore\Exceptions\RegisterClassTypeNotFoundException;
use JobMetric\PackageCore\Exceptions\ViewFolderNotFoundException;
use JobMetric\PackageCore\PackageCore;
use JobMetric\PackageCore\PackageCoreServiceProvider;
use Throwable;

class BanIpServiceProvider extends PackageCoreServiceProvider
{
    /**
     * @param PackageCore $package
     *
     * @return void
     * @throws MigrationFolderNotFoundException
     * @throws RegisterClassTypeNotFoundException
     * @throws ViewFolderNotFoundException
     * @throws ConsoleKernelFileNotFoundException
     */
    public function configuration(PackageCore $package): void
    {
        $package->name('laravel-ban-ip')
            ->hasConfig()
            ->hasMigration()
            ->hasTranslation()
            ->hasView()
            ->hasConsoleKernel()
            ->registerCommand(Commands\BanIpRemove::class)
            ->registerClass('BanIp', BanIp::class);
    }

    /**
     * after boot package
     *
     * @return void
     * @throws Throwable
     */
    public function afterBootPackage(): void
    {
        if (checkDatabaseConnection() && !app()->runningInConsole() && !app()->runningUnitTests()) {
            $result = BanIpModels::query()
                ->where('ip', '=', request()->ip())
                ->where('expired_at', '>', now()->format('Y-m-d H:i:s'))
                ->get();

            if ($result->count()) {
                $banIp = $result->first();
                if ($banIp->expired_at) {
                    $diff = now()->diffInSeconds($banIp->expired_at);
                } else {
                    $diff = null;
                }

                $data = [
                    'reason' => $banIp->reason,
                    'timer' => $diff,
                ];

                if (request()->wantsJson()) {
                    echo response()->json(array_merge(['message' => 'Your IP has been banned.'], $data));
                } else {
                    echo view('ban-ip::ban-ip', $data)->render();
                }

                die;
            }
        }
    }
}
