<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;

class RegisterFlash implements ServiceProviderInterface
{
    /**
     * Registers the flash and flash session services with the Twitter Bootstrap classes
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        /**
         * Register the flash service
         */
        $di->setShared('flash', function () {
            return new FlashDirect([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        });

        /**
         * Register the session flash service
         */
        $di->setShared('flashSession', function () {
            return new FlashSession([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        });
    }
}
