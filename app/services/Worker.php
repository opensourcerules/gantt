<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Services\Authentication as AuthenticationService;

class Worker
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var Workers
     */
    private $workerModel;

    /**
     * Constructs the needed services, set in DI, for authentication service and model
     * @param Authentication $authenticationService
     * @param Workers $workerModel
     */
    public function __construct(
        AuthenticationService $authenticationService,
        Workers $workerModel
    ) {
        $this->authenticationService = $authenticationService;
        $this->workerModel = $workerModel;
    }

    /**
     * Registers the worker via model in db
     * @param array $worker
     * @return bool
     */
    public function register(array $worker): bool
    {
        if ('1' === $worker['admin']) {
            $worker['password'] = $this->authenticationService->hashPassword($worker['password']);
        }

        $this->workerModel->setLastName($worker['lastName']);
        $this->workerModel->setFirstName($worker['firstName']);
        $this->workerModel->setEmail($worker['email']);
        $this->workerModel->setPassword($worker['password']);
        $this->workerModel->setAdmin($worker['admin']);

        return $this->workerModel->create();
    }
}
