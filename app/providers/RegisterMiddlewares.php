<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use GanttDashboard\App\Middleware\Authentication as AuthenticationMiddleware;
use GanttDashboard\App\Services\Authentication as AuthenticationService;

class RegisterMiddlewares implements ServiceProviderInterface
{
    /**
     * Register Middleware
     * @param DiInterface $di
     * @retunr void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared(AuthenticationMiddleware::class, function () use ($di) {

            return new AuthenticationMiddleware(
                $di->get(AuthenticationService::class),
                $di->get('session'),
                $di->get('acl'),
                $di->get('router')
            );
        });

        $eventsManager = $di->getShared('eventsManager');
        $eventsManager->attach('application', $di->getShared(AuthenticationMiddleware::class));
    }
}
