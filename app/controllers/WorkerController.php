<?php

namespace GanttDashboard\App\Controllers;

use GanttDashboard\App\Services\Worker as WorkerService;

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
     */
    public function loginAction(string $accessKey = '')
    {
        $redirect = ['for' => 'notFound'];
        $redirectCode = 404;

        if (true === $this->workerService->login($accessKey)) {
            $this->flashSession->success('You are now logged in as ADMIN!');
            $redirect = ['for' => 'home'];
            $redirectCode = 200;
        }

        $this->response->redirect($redirect, false, $redirectCode);
    }

    /**
     * Performs the logout
     */
    public function logoutAction()
    {
        $this->workerService->logout();
        $this->flashSession->success('You are now logged out!');
        $this->response->redirect(['for' => 'home'], false, 200);
    }
}
