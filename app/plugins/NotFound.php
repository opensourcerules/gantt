<?php

namespace GanttDashboard\App\Plugins;

use Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class NotFound
{
    /**
     * Handle not found exceptions
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, Exception $exception)
    {
        if ($exception instanceof DispatchException) {
            $dispatcher->forward([
                'controller' => 'Index',
                'action' => 'notFound',
            ]);
        }

        return false;
    }
}
