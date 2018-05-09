<?php

class WorkersHelpers
{
    /**
     * @var SessionHandler
     */
    private $session;

    /**
     * WorkersHelpers constructor.
     */
    public function __construct()
    {
        $this->session = \Phalcon\Di::getDefault()->get('session');
    }

    /**
     * @param string $access
     * @param int $workerId
     * @return bool
     */
    public function login(string $access, int $workerId = 1)
    {
        $admin = Workers::findFirst(
            [
                "id = :id:",
                "bind" => [
                    "id"    => $workerId,
                ]
            ]
        );

        if (false === password_verify($access, $admin->getPassword())) {
            return false;
        }

        return $this->session->set('worker_session', $admin->getId());
    }

    /**
     * Removes the session variable stored by login
     */
    public function logout()
    {
        $this->session->remove('worker_session');
    }
}
