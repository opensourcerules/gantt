<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use GanttDashboard\App\Validators\Worker as WorkerValidator;

class RegisterServices implements ServiceProviderInterface
{
    /**
     * Register services
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        /**
         * Register Authentication service
         */
        $di->setShared(AuthenticationService::class, function () use ($di) {
            return new AuthenticationService(
                $di->get('session')
            );
        });

        /**
         * Register Worker service
         */
        $di->setShared(WorkerService::class, function () use ($di) {
            return new WorkerService(
                $di->get(WorkerValidator::class)
            );
        });
    }
}
