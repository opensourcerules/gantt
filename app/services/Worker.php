<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Security;

class Worker
{
    /**
     * @var SessionAdapter
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
     * Constructs the needed services, set in DI, for session, security and model
     * @param SessionAdapter $sessionService
     * @param Security $securityService
     * @param Workers $workerModel
     */
    public function __construct(
        SessionAdapter $sessionService,
        Security $securityService,
        Workers $workerModel
    ) {
        $this->sessionService = $sessionService;
        $this->securityService = $securityService;
        $this->workerModel = $workerModel;
    }

    /**
     * Searches for the first match between the accessKey and the password of admin type
     * workers from the database and if found, sets the worker's id in the session
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
