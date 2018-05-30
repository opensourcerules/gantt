<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

class RegisterConfig implements ServiceProviderInterface
{
    /**
     * Register shared configuration service
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('config', function () {
            return include APP_PATH . '/config/config.php';
        });
    }
}
