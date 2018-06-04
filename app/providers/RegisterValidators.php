<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use GanttDashboard\App\Validators\Worker as WorkerValidator;

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
    }
}
