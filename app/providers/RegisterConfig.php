<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Config;

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
            require APP_PATH . '/config/config.php';

            return new Config($settings);
        });
    }
}
