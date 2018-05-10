<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use Phalcon\Session\Adapter\Files;
use Phalcon\Security;

class HandleWorker
{
    /**
     * @var Files
     */
    private $session;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var Workers
     */
    private $worker;

    /**
     * HandleWorker constructor.
     * @param Files $sessionService
     * @param Security $securityService
     * @param Workers $worker
     */
    public function __construct(
        Files $sessionService,
        Security $securityService,
        Workers $worker
    ) {
        $this->session = $sessionService;
        $this->security = $securityService;
        $this->worker = $worker;
    }

    /**
     * @param string $accessKey
     * @return boolean
     */
    public function login(string $accessKey)
    {
        $admins = $this->worker->find([
            'admin = 1',
        ]);

        foreach ($admins as $admin) {
            if (true === $this->security->checkHash($accessKey, $admin->getPassword())) {
                $this->session->set('worker_session', $admin->getId());

                return true;
            }
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
