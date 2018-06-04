<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Annotations\Adapter\Memory;

class RegisterAnnotations implements ServiceProviderInterface
{
    /**
     * Register Worker Validator
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('annotations', Memory::class);
    }
}
