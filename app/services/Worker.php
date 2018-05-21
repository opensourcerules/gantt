<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Validators\Worker as WorkerValidator;

class Worker
{
    /**
     * @var WorkerValidator
     */
    private $workerValidator;

    /**
     * @var Workers
     */
    private $workerModel;

    /**
     * Constructs the needed services, set in DI, for validators service and model
     * @param WorkerValidator $workerValidator
     * @param Workers $workerModel
     */
    public function __construct(
        WorkerValidator $workerValidator,
        Workers $workerModel
    ) {
        $this->workerValidator = $workerValidator;
        $this->workerModel = $workerModel;
    }

    /**
     * Registers the worker via model in db
     * @param array $worker
     * @return \Phalcon\Validation\Message\Group
     */
    public function register(array $worker): object
    {
        $errors = $this->workerValidator->validate($worker);

        if (0 == $errors->count()) {
            $this->workerModel->setLastName($worker['lastName']);
            $this->workerModel->setFirstName($worker['firstName']);
            $this->workerModel->setEmail($worker['email']);
            $this->workerModel->create();
        }

        return $errors;
    }
}
