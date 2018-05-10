<?php

namespace GanttDashboard\App\Controllers;

use GanttDashboard\App\Services\HandleWorker;

class WorkerController extends ControllerBase
{
    /**
     * @var HandleWorker
     */
    private $handleWorker;

    /**
     * Initialises the handleWorker property
     */
    public function onConstruct()
    {
        $this->handleWorker = $this->getDi()->get(HandleWorker::class);
    }

    /**
     * Performs the login
     * @param string $accessKey
     */
    public function loginAction(string $accessKey = '')
    {
        if ('' === $accessKey) {
            $this->response->redirect('');
        }

        $handleWorker = $this->handleWorker;
        $success = $handleWorker->login($accessKey);

        if (true === $success) {
            $this->response->redirect('');
        }

        $this->flash->notice('Wrong url.');
    }

    /**
     * Performs the logout
     */
    public function logoutAction()
    {
        $handleWorker = $this->handleWorker;
        $handleWorker->logout();

        $this->response->redirect('');
    }
}
