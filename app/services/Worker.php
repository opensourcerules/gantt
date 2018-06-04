<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Validation\Message\Group as MessageGroup;
use Phalcon\Mvc\Model;

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
     * @return MessageGroup
     */
    public function register(Workers $workerModel, array $worker): MessageGroup
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
     * @return ResultsetInterface
     */
    public function getSortedWorkers(): ResultsetInterface
    {
        return Workers::find([
            'order' => 'firstName, lastName, email'
        ]);
    }

    /**
     * Gets worker from database via model
     * @param int $id
     * @return Model
     */
    public function getWorker(int $id): Model
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
     * @return MessageGroup
     */
    public function edit(array $workerUpdate): MessageGroup
    {
        $errors = $this->workerValidator->validate($workerUpdate);

        if (0 == $errors->count()) {

            /** @var $worker Workers */
            $worker = $this->getWorker($workerUpdate['id']);
            $worker->setLastName($workerUpdate['lastName']);
            $worker->setFirstName($workerUpdate['firstName']);
            $worker->setEmail($workerUpdate['email']);
            $worker->update();
        }

        return $errors;
    }
}
