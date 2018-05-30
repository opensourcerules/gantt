<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;

class RegisterDispatcher implements ServiceProviderInterface
{
    /**
     * Register a dispatcher
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('GanttDashboard\\App\\Controllers');

            return $dispatcher;
        });
    }
}
