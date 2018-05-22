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
     * Registers the worker via model in database
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

    /**
     *Gets all the workers with full name from database via model
     * @return array
     */
    public function getWorkers(): array
    {
        return $this->workerModel->getWorkersWithFullName();
    }

    /**
     * Updates the worker via model in database
     * @param array $workerUpdate
     * @return \Phalcon\Validation\Message\Group
     */
    public function edit(array $workerUpdate): object
    {
        $errors = $this->workerValidator->validate($workerUpdate);

        if (0 == $errors->count()) {
            /**
             * @var $worker \GanttDashboard\App\Models\Workers
             */
            $worker = Workers::findFirstById($workerUpdate['id']);
            $worker->setLastName($workerUpdate['lastName']);
            $worker->setFirstName($workerUpdate['firstName']);
            $worker->setEmail($workerUpdate['email']);
            $worker->update();
        }

        return $errors;
    }
}
