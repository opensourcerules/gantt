<?php

class WorkerController extends ControllerBase
{
    /**
     * @var WorkersHelpers
     */
    private $helper;

    /**
     * Initialises the helper property
     */
    public function initialize()
    {
        if (null === $this->helper) {
            $this->helper = \Phalcon\Di::getDefault()->get('workersHelpers');
        }
    }

    /**
     * Performs the login
     * @param string $access
     * @return void
     */
    public function loginAction(string $access = '')
    {
        if (null === $access) {
            $this->response->redirect('');
            return;
        }
        $workersHelpers = $this->helper;
        $success = $workersHelpers->login($access);

        if (false === $success) {
            $this->flash->notice('Wrong url.');

            return;
        }

        $this->response->redirect('');
    }

    /**
     * Performs the logout
     * @return void
     */
    public function logoutAction()
    {
        $workersHelpers = $this->helper;
        $workersHelpers->logout();
        $this->response->redirect('');
    }
}
