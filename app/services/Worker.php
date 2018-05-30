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
     * Constructs the needed service, set in DI, for validators service
     * @param WorkerValidator $workerValidator
     */
    public function __construct(
        WorkerValidator $workerValidator
    ) {
        $this->workerValidator = $workerValidator;
    }

    /**
     * Registers the worker via model in database
     * @param Workers $workerModel
     * @param array $worker
     * @return \Phalcon\Validation\Message\Group
     */
    public function register(Workers $workerModel, array $worker): object
    {
        $errors = $this->workerValidator->validate($worker);

        if (0 == $errors->count()) {
            $workerModel->setLastName($worker['lastName']);
            $workerModel->setFirstName($worker['firstName']);
            $workerModel->setEmail($worker['email']);
            $workerModel->create();
        }

        return $errors;
    }

    /**
     * Gets all the workers sorted from database via model
     * @return \Phalcon\MVC\Model\ResultsetInterface
     */
    public function getSortedWorkers(): object
    {
        return Workers::find([
            'order' => 'firstName, lastName, email'
        ]);
    }

    /**
     * Gets worker from database via model
     * @param int $id
     * @return \Phalcon\MVC\Model\ResultsetInterface
     */
    public function getWorker(int $id): object
    {
        return Workers::findFirst([
            'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
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
            $worker = $this->getWorker($workerUpdate['id']);
            $worker->setLastName($workerUpdate['lastName']);
            $worker->setFirstName($workerUpdate['firstName']);
            $worker->setEmail($workerUpdate['email']);
            $worker->update();
        }

        return $errors;
    }
}
