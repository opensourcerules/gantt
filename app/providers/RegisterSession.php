<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class RegisterSession implements ServiceProviderInterface
{
    /**
     * Start the session the first time some component request the session service
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('session', function () {
            $session = new SessionAdapter();
            $session->start();

            return $session;
        });
    }
}
