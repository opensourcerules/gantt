<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use Phalcon\Validation\Message\Group as GroupMessages;

class Worker
{
    /**
     * @var WorkerValidator
     */
    private $workerValidator;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var Workers
     */
    private $workerModel;

    /**
     * Constructs the needed services, set in DI, for validators, authentication service and model
     * @param WorkerValidator $workerValidator
     * @param Authentication $authenticationService
     * @param Workers $workerModel
     */
    public function __construct(
        WorkerValidator $workerValidator,
        AuthenticationService $authenticationService,
        Workers $workerModel
    ) {
        $this->workerValidator = $workerValidator;
        $this->authenticationService = $authenticationService;
        $this->workerModel = $workerModel;
    }

    /**
     * Registers the worker via model in db
     * @param array $worker
     * @return GroupMessages
     */
    public function register(array $worker): object
    {
        $errors = $this->workerValidator->validate($worker);

        if (0 == $errors->count()) {
            if ('1' === $worker['admin']) {
                $worker['password'] = $this->authenticationService->hashPassword($worker['password']);
            }

            $this->workerModel->setLastName($worker['lastName']);
            $this->workerModel->setFirstName($worker['firstName']);
            $this->workerModel->setEmail($worker['email']);
            $this->workerModel->setPassword($worker['password']);
            $this->workerModel->setAdmin($worker['admin']);
            $this->workerModel->create();
        }

        return $errors;
    }
}
