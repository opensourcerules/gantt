<?php

class WorkersServices
{
    /**
     * @var Phalcon\Session\Adapter\Files
     */
    private $session;

    /**
     * @var \Phalcon\Security
     */
    private $security;

    /**
     * WorkersServices constructor.
     * @param $sessionService
     * @param $securityService
     */
    public function __construct($sessionService, $securityService)
    {
        $this->session = $sessionService;
        $this->security = $securityService;
    }

    /**
     * @param string $access
     * @param int $workerId
     * @return bool
     */
    public function login(string $access, int $workerId = 1)
    {
        /**
         * @var Workers $admin
         */
        $admin = Workers::findFirst([
            'id = :id:',
            'bind' => [
                'id' => $workerId,
            ]
        ]);

        if (true === $this->security->checkHash($access, $admin->getPassword())) {
            $this->session->set('worker_session', $admin->getId());

            return true;
        }

        return false;
    }

    /**
     * Removes the session variable stored by login
     */
    public function logout()
    {
        $this->session->remove('worker_session');
    }
}
