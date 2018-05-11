<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use Phalcon\Session\Adapter\Files;
use Phalcon\Security;

class Worker
{
    /**
     * @var Files
     */
    private $sessionService;

    /**
     * @var Security
     */
    private $securityService;

    /**
     * @var Workers
     */
    private $workerModel;

    /**
     * Worker constructor.
     * @param Files $sessionService
     * @param Security $securityService
     * @param Workers $workerModel
     */
    public function __construct(
        Files $sessionService,
        Security $securityService,
        Workers $workerModel
    ) {
        $this->sessionService = $sessionService;
        $this->securityService = $securityService;
        $this->workerModel = $workerModel;
    }

    /**
     * @param string $accessKey
     * @return boolean
     */
    public function login(string $accessKey)
    {
        $admins = $this->workerModel->find([
            'admin = 1',
        ]);

        foreach ($admins as $admin) {
            if (true === $this->securityService->checkHash($accessKey, $admin->getPassword())) {
                $this->sessionService->set('worker_session', $admin->getId());

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
        $this->sessionService->remove('worker_session');
    }
}
