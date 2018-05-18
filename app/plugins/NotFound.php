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
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @param Exception $exception
     * @return bool
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, Exception $exception): bool
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
