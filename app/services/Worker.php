<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Validation\Message\Group as MessageGroup;
use Phalcon\Mvc\Model;
use GanttDashboard\App\Services\WorkerProject as WorkerProjectService;
use GanttDashboard\App\Services\History as HistoryService;
use GanttDashboard\App\Validators\WorkerProject as WorkerProjectValidator;

class Worker
{
    /**
     * @var WorkerValidator
     */
    private $workerValidator;

    /**
     * @var WorkerProjectService
     */
    private $workerProjectService;

    /**
     * @var HistoryService
     */
    private $historyService;

    /**
     * @var WorkerProjectValidator
     */
    private $workerProjectValidator;

    /**
     * Constructs the needed services, set in DI
     * @param WorkerValidator $workerValidator
     * @param WorkerProject $workerProjectService
     * @param HistoryService $historyService
     * @param WorkerProjectValidator $workerProjectValidator
     */
    public function __construct(
        WorkerValidator $workerValidator,
        WorkerProjectService $workerProjectService,
        HistoryService $historyService,
        WorkerProjectValidator $workerProjectValidator
    ) {
        $this->workerValidator = $workerValidator;
        $this->workerProjectService = $workerProjectService;
        $this->historyService = $historyService;
        $this->workerProjectValidator = $workerProjectValidator;
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

    /**
     * Assigns the worker via models in database
     * @param int $workerId
     * @param array $assignments
     * @return MessageGroup
     */
    public function assign(int $workerId, array $assignments): MessageGroup
    {
        $errors = $this->workerProjectValidator->validate($assignments);

        if (0 == $errors->count()) {
            foreach ($assignments['projects'] as $project) {
                if (true == isset($project['value']) &&
                    true === $this->workerProjectService->add($workerId, $project['id'])) {
                    $this->historyService->add($workerId, $project['id'], $project['reason']);
                }
            }
        }

        return $errors;
    }
}
