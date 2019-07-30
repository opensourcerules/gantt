<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Models\WorkersProjects;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Resultset\Simple as ResultsetSimple;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Manager as ModelsManager;
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
     * @var ModelsManager
     */
    private $modelsManager;

    /**
     * Constructs the needed services, set in DI
     * @param WorkerValidator $workerValidator
     * @param WorkerProject $workerProjectService
     * @param HistoryService $historyService
     * @param WorkerProjectValidator $workerProjectValidator
     * @param ModelsManager $modelsManager
     */
    public function __construct(
        WorkerValidator $workerValidator,
        WorkerProjectService $workerProjectService,
        HistoryService $historyService,
        WorkerProjectValidator $workerProjectValidator,
        ModelsManager $modelsManager
    ) {
        $this->workerValidator = $workerValidator;
        $this->workerProjectService = $workerProjectService;
        $this->historyService = $historyService;
        $this->workerProjectValidator = $workerProjectValidator;
        $this->modelsManager = $modelsManager;
    }

    /**
     * Registers the worker via model in database
     * @param Workers $workerModel
     * @param array $worker
     * @return WorkerValidator
     */
    public function register(Workers $workerModel, array $worker): WorkerValidator
    {
        $errors = $this->workerValidator->validate($worker);

        if (0 == $errors->count()) {
            $workerModel->setLastName($worker['lastName']);
            $workerModel->setFirstName($worker['firstName']);
            $workerModel->setEmail($worker['email']);
            $workerModel->create();
        }

        return $this->workerValidator;
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
     * Gets all the assigned workers sorted from database via model
     * @return ResultsetSimple
     */
    public function getSortedAssignedWorkers(): ResultsetSimple
    {
        return $this->modelsManager->createBuilder()
            ->columns([
                'id',
                'lastName',
                'firstName',
                'email'
            ])
            ->from(Workers::class)
            ->join(WorkersProjects::class, 'id = workersProjects.workerId', 'workersProjects')
            ->groupBy('id')
            ->orderBy('firstName, lastName, email')
            ->getQuery()
            ->execute();
    }

    /**
     * Gets all the unassigned workers sorted from database via model
     * @return ResultsetSimple
     */
    public function getUnAssignedWorkers(): ResultsetSimple
    {
        return $this->modelsManager->createBuilder()
            ->columns([
                'id',
                'lastName',
                'firstName',
                'email'
            ])
            ->from(Workers::class)
            ->leftJoin(WorkersProjects::class, 'id = workersProjects.workerId', 'workersProjects')
            ->where('workersProjects.workerId IS NULL')
            ->orderBy('firstName, lastName, email')
            ->getQuery()
            ->execute();
    }

    /**
     * Gets worker from database via model
     * @param int $id
     * @return Workers
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
     * @return WorkerValidator
     */
    public function edit(array $workerUpdate): WorkerValidator
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

        return $this->workerValidator;
    }

    /**
     * Assigns the worker via models in database
     * @param int $workerId
     * @param array $assignments
     * @return WorkerProjectValidator
     */
    public function assign(int $workerId, array $assignments): WorkerProjectValidator
    {
        $errors = $this->workerProjectValidator->validate($assignments);

        if ($errors->count() > 0) {
            return $this->workerProjectValidator;
        }

        foreach ($assignments['projects'] as $project) {
            if (false == isset($project['value'])) {
                continue;
            }

            if (true === $this->workerProjectService->add($workerId, $project['id'])) {
                $this->historyService->add($workerId, $project['id'], $project['reason']);
            }
        }

        return $this->workerProjectValidator;
    }
    
    /**
     * Unassigns/removes the worker from project(s) via models in database
     * @param int $workerId
     * @param array $unAssignments
     * @return WorkerProjectValidator
     */
    public function unAssign(int $workerId, array $unAssignments): WorkerProjectValidator
    {
        $errors = $this->workerProjectValidator->validate($unAssignments);
        
        if ($errors->count() > 0) {
            return $this->workerProjectValidator;
        }
        
        foreach ($unAssignments['projects'] as $project) {
            if (false == isset($project['value'])) {
                continue;
            }

            if (true === $this->workerProjectService->remove($workerId, $project['id'])) {
                $this->historyService->unAssign($workerId, $project['id'], $project['reason']);
            }
        }

        return $this->workerProjectValidator;
    }
}
