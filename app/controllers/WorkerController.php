<?php

class WorkerController extends ControllerBase
{
    /**
     * @var WorkersServices
     */
    private $helper;

    /**
     * Initialises the helper property
     */
    public function initialize()
    {
        $this->helper = $this->getDi()->get('WorkersServices');
    }

    /**
     * Performs the login
     * @param string $access
     */
    public function loginAction(string $access = '')
    {
        if (null === $access) {
            $this->response->redirect('');
        }
        $workersHelpers = $this->helper;
        $success = $workersHelpers->login($access);

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
        $workersHelpers = $this->helper;
        $workersHelpers->logout();
        $this->response->redirect('');
    }
}
