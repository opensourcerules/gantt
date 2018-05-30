<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

class RegisterView implements ServiceProviderInterface
{
    /**
     * Register the views with Volt
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        /**
         * Register Volt as a service
         */
        $di->setShared('voltService', function ($view) use ($di) {
            $config = $di->get('config');

            $volt = new VoltEngine($view, $di);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        });

        /**
         * Setting up the view component
         */
        $di->setShared('view', function () use ($di) {
            $config = $di->get('config');

            $view = new View();
            $view->setDI($di);
            $view->setViewsDir($config->application->viewsDir);
            $view->setMainView('main');

            $view->registerEngines([
                '.phtml' => 'voltService'
            ]);

            return $view;
        });
    }
}
