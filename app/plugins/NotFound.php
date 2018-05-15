<?php

namespace GanttDashboard\App\Plugins;

use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class NotFound
{
    public function beforeException(Event $event, Dispatcher $dispatcher, Exception $exception)
    {
        /**
         * Default error controller and action
         */
        $action = 'index';

        /**
         * Handle 404 exceptions
         */
        if ($exception instanceof DispatchException) {
            $action = 'notFound';
        }

        $dispatcher->forward(
            [
                'controller' => 'Index',
                'action'     => $action,
            ]
        );

        return false;
    }
}
