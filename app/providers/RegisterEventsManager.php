<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Events\Manager as EventsManager;

class RegisterEventsManager implements ServiceProviderInterface
{
    /**
     * Register an events manager
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('eventsManager', EventsManager::class);
    }
}
