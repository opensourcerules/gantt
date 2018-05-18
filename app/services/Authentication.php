<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Security;
use Phalcon\Mvc\Model\Resultset\Simple as ResultSetSimple;

class Authentication
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
     * @var Worker
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
     * @return bool
     */
    public function login(string $accessKey): bool
    {
        /**
         * @var ResultSetSimple $admins
         */
        $admins = $this->workerModel->find([
            'admin = 1',
        ]);

        /**
         * @var Workers $admin
         */
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
     * @return void
     */
    public function logout(): void
    {
        $this->sessionService->remove('worker_session');
    }

    /**
     * Checks if worker is logged in
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        $admin = $this->sessionService->get('worker_session');

        if (false === empty($admin)) {
            return true;
        }

        return false;
    }

    /**
     * Hashes password
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return $this->securityService->hash($password);
    }
}
