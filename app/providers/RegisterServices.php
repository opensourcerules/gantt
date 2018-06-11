<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use GanttDashboard\App\Services\Project as ProjectService;
use GanttDashboard\App\Validators\Project as ProjectValidator;
use GanttDashboard\App\Validators\WorkerProject as WorkerProjectValidator;
use GanttDashboard\App\Services\History as HistoryService;
use GanttDashboard\App\Services\WorkerProject as WorkerProjectService;

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
                $di->get(WorkerValidator::class),
                $di->get(WorkerProjectService::class),
                $di->get(HistoryService::class),
                $di->get(WorkerProjectValidator::class)
            );
        });
        
        /**
         * Register Project service
         */
        $di->setShared(ProjectService::class, function () use ($di) {
            return new ProjectService(
                $di->get(ProjectValidator::class)
            );
        });

        /**
         * Register WorkerProject service
         */
        $di->setShared(WorkerProjectService::class, WorkerProjectService::class);

        /**
         * Register History service
         */
        $di->setShared(HistoryService::class, HistoryService::class);
    }
}
