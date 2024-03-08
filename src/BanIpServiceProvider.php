<?php

namespace JobMetric\BanIp;

use JobMetric\BanIp\Facades\BanIp as BanIpFacade;
use JobMetric\PackageCore\Exceptions\ConsoleKernelFileNotFoundException;
use JobMetric\PackageCore\Exceptions\MigrationFolderNotFoundException;
use JobMetric\PackageCore\Exceptions\RegisterClassTypeNotFoundException;
use JobMetric\PackageCore\Exceptions\ViewFolderNotFoundException;
use JobMetric\PackageCore\PackageCore;
use JobMetric\PackageCore\PackageCoreServiceProvider;

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

    public function afterBootPackage(): void
    {
        if (checkDatabaseConnection() && !app()->runningInConsole() && !app()->runningUnitTests()) {
            $result = BanIpFacade::all([
                ['ip', request()->ip()],
                ['expired_at', '>', now()->format('Y-m-d H:i:s')]
            ]);

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
