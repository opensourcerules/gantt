<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Config\Factory;

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
            $options = [
                'filePath' => APP_PATH . '/config/config',
                'adapter'  => 'php',
            ];

            return Factory::load($options);
        });
    }
}
