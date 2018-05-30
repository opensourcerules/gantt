<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

class RegisterRouter implements ServiceProviderInterface
{
    /**
     * Registers router
     * @param DiInterface $di\
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('router', function () {
            return include APP_PATH . '/config/router.php';
        });
    }
}
