<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\Url as UrlResolver;

class RegisterUrl implements ServiceProviderInterface
{
    /**
     * Register the URL component that is used to generate all kind of urls in the application
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('url', function () use ($di) {
            $config = $di->get('config');

            $url = new UrlResolver();
            $url->setBaseUri($config->application->baseUri);

            return $url;
        });
    }
}
