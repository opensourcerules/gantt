<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use GanttDashboard\App\Validators\Project as ProjectValidator;
use GanttDashboard\App\Validators\WorkerProject as WorkerProjectValidator;
use GanttDashboard\App\Validators\DateInterval as DateValidator;

class RegisterValidators implements ServiceProviderInterface
{
    /**
     * Register Worker Validator
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared(WorkerValidator::class, WorkerValidator::class);

        $di->setShared(ProjectValidator::class, ProjectValidator::class);

        $di->setShared(WorkerProjectValidator::class, WorkerProjectValidator::class);

        $di->setShared(DateValidator::class, DateValidator::class);
    }
}
