<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\Router\Group as RouterGroup;
use Phalcon\Mvc\Router;

class RegisterRouter implements ServiceProviderInterface
{
    /**
     * Registers router
     * @param DiInterface $di\
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('router', function () {
            $router = new Router(false);

            /**
             * Set 404 path
             */
            $router->notFound([
                'controller' => 'Index',
                'action'     => 'notFound',
            ]);

            /**
             * Create a group with index controller
             */
            $index = new RouterGroup([
                'controller' => 'Index',
            ]);

            $index->addGet('/', [
                'action' => 'index',
            ])->setName('home');

            $index->addGet('/index/notfound', [
                'action' => 'notFound',
            ])->setName('notFound');

            $router->mount($index);

            /**
             * Create a group with a Worker controller
             */
            $worker = new RouterGroup([
                'controller' => 'Worker',
            ]);

            $worker->setPrefix('/worker');

            $worker->add('/register', [
                'action' => 'register',
            ])->via([
                'POST',
                'GET'
            ])->setName('registerWorker');

            $worker->add('/edit/{id:[0-9]+}', [
                'action' => 'edit',
            ])->via([
                'POST',
                'GET'
            ])->setName('editWorker');

            $worker->addGet('/edit', [
                'action' => 'beforeEdit',
            ])->setName('beforeEditWorker');

            $worker->add('/assign/{id:[0-9]+}', [
                'action' => 'assign',
            ])->via([
                'POST',
                'GET'
            ])->setName('assignWorker');

            $worker->addGet('/assign', [
                'action' => 'beforeAssign',
            ])->setName('beforeAssignWorker');

            $worker->add('/history/{id:[0-9]+}', [
                'action' => 'history',
            ])->via([
                'POST',
                'GET'
            ])->setName('historyWorker');

            $worker->addGet('/history', [
                'action' => 'beforeHistory',
            ])->setName('beforeHistoryWorker');
            
            $worker->add('/unassign/{id:[0-9]+}', [
                'action' => 'unAssign',
            ])->via([
                'POST',
                'GET'
            ])->setName('unAssignWorker');

            $worker->addGet('/unassign', [
                'action' => 'beforeUnAssign',
            ])->setName('beforeUnAssignWorker');

            $worker->addGet('/logout', [
                'action' => 'logout',
            ]);

            $worker->addGet('/login/{accessKey:[a-zA-Z0-9]+}', [
                'action' => 'login',
            ]);

            $router->mount($worker);
            
            /**
             * Create a group with a Project controller
             */
            $project = new RouterGroup([
                'controller' => 'Project',
            ]);

            $project->setPrefix('/project');

            $project->add('/register', [
                'action' => 'register',
            ])->via([
                'POST',
                'GET'
            ])->setName('registerProject');

            $project->add('/edit/{id:[0-9]+}', [
                'action' => 'edit',
            ])->via([
                'POST',
                'GET'
            ])->setName('editProject');

            $project->addGet('/edit', [
                'action' => 'beforeEdit',
            ])->setName('beforeEditProject');

            $project->add('/history/{id:[0-9]+}', [
                'action' => 'history',
            ])->via([
                'POST',
                'GET'
            ])->setName('historyProject');

            $project->addGet('/history', [
                'action' => 'beforeHistory',
            ])->setName('beforeHistoryProject');

            $router->mount($project);

            /**
             * Create a group with a History controller
             */
            $history = new RouterGroup([
                'controller' => 'History',
            ]);

            $history->setPrefix('/history');

            $history->add('', [
                'action' => 'index',
            ])->via([
                'POST',
                'GET'
            ])->setName('indexHistory');

            $router->mount($history);

            return $router;
        });
    }
}
