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
        $redirect = 'index/notFound404';
        $success = $this->workerService->login($accessKey);

        if (true === $success) {
            $this->flashSession->success('You are now logged in as ADMIN!');
            $redirect = '';
        }

        $this->response->redirect($redirect);
    }

    /**
     * Performs the logout
     */
    public function logoutAction()
    {
        $this->workerService->logout();
        $this->flashSession->success('You are now logged out!');
        $this->response->redirect('');
    }
}
