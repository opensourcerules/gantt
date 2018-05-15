<?php

namespace GanttDashboard\App\Controllers;

use GanttDashboard\App\Services\Worker as WorkerService;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

class WorkerController extends ControllerBase
{
    /**
     * @var WorkerService
     */
    private $workerService;

    /**
     * Initialises the workerService property
     */
    public function onConstruct()
    {
        $this->workerService = $this->getDi()->get(WorkerService::class);
    }

    /**
     * Performs the login
     * @param string $accessKey
     * @return Response|ResponseInterface
     */
    public function loginAction(string $accessKey = '')
    {
        if (true === $this->workerService->login($accessKey)) {
            $this->flashSession->success('You are now logged in as ADMIN!');

            return $this->response->redirect(['for' => 'home'], false, 200);
        }

        $this->dispatcher->forward([
            'controller' => 'Index',
            'action'     => 'notFound',
        ]);

        return $this->response->setStatusCode(404, 'Not Found');
    }

    /**
     * Performs the logout
     * @return Response|ResponseInterface
     */
    public function logoutAction()
    {
        $this->workerService->logout();
        $this->flashSession->success('You are now logged out!');

        return $this->response->redirect(['for' => 'home'], false, 200);
    }
}
