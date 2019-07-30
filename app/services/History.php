<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\History as HistoryModel;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Resultset\Simple as ResultsetSimple;
use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Models\Projects;

class History
{
    /**
     * SQL conditions for interval filter
     */
    const INTERVAL_FILTER = '(
        (dateStart <= :startInterval: AND :endInterval: <= dateEnd)
        OR (dateStart >= :startInterval: AND :endInterval: >= dateEnd)
        OR (dateStart >= :startInterval: AND :endInterval: <= dateEnd AND dateStart <= :endInterval:)
        OR (dateStart <= :startInterval: AND :endInterval: >= dateEnd AND dateEnd >= :startInterval:)
        OR (dateEnd IS NULL AND :endInterval: >= dateStart)
    )';

    /**
     * @var ModelsManager
     */
    private $modelsManager;

    /**
     * Constructs the needed service, set in DI
     * @param ModelsManager $modelsManager
     */
    public function __construct(
        ModelsManager $modelsManager
    ) {
        $this->modelsManager = $modelsManager;
    }

    /**
     * Inserts the $workerId - $projectId pair into db via model
     * @param int $workerId
     * @param int $projectId
     * @param string $reason
     * @return void
     */
    public function add(int $workerId, int $projectId, string $reason): void
    {
        $historyModel = new HistoryModel();
        $historyModel->setWorkerId($workerId);
        $historyModel->setProjectId($projectId);
        $historyModel->setDateStart(time());
        $historyModel->setReason('{"assign":"' . $reason . '","unassign":""}');

        $historyModel->create();
    }

    /**
     * Inserts the dateEnd for $workerId - $projectId pair into db via model
     * @param int $workerId
     * @param int $projectId
     * @param string $reason
     * @return void
     */
    public function unAssign(int $workerId, int $projectId, string $reason): void
    {
        $historyModel = HistoryModel::findFirst([
            'workerId = :workerId: and projectId = :projectId: and dateEnd IS NULL',
            'bind' => [
                'workerId'  => $workerId,
                'projectId' => $projectId
            ]
        ]);

        $newReason = json_decode($historyModel->getReason(), true);
        $newReason['unassign'] = $reason;
        $historyModel->setReason(json_encode($newReason));
        $historyModel->setDateEnd(time());

        $historyModel->update();
    }

    /**
     * Returns the actual assignments
     * @return ResultsetSimple
     */
    public function getActualAssignments(): ResultsetSimple
    {
        return $this->modelsManager->createBuilder()
            ->columns([
                'workerId',
                'projectId',
                'workers.lastName',
                'workers.firstName',
                'workers.email',
                'projects.name',
                'reason',
                'dateStart',
                'dateEnd'
            ])
            ->from(HistoryModel::class)
            ->where('dateEnd is NULL')
            ->join(Workers::class, 'workers.id = workerId', 'workers')
            ->join(Projects::class, 'projects.id = projectId', 'projects')
            ->getQuery()
            ->execute();
    }

    /**
     * Returns the bind array for query
     * @param int $startInterval
     * @param int $endInterval
     * @param int $id
     * @return array
     */
    public function getBindParameters(int $startInterval, int $endInterval, int $id = 0):array
    {
        if ($id == 0) {
            return ['startInterval' => $startInterval, 'endInterval' => $endInterval];
        }

        return ['id' => $id, 'startInterval' => $startInterval, 'endInterval' => $endInterval];
    }

    /**
     * Returns the history assignments from a date interval
     * @param string $start
     * @param string $end
     * @return ResultsetSimple
     */
    public function getHistoryAssignments(string $start, string $end): ResultsetSimple
    {
        $startInterval = strtotime($start . 'T00:00:00 UTC');
        $endInterval = strtotime($end . 'T23:59:59 UTC');

        return $this->modelsManager->createBuilder()
            ->columns([
                'workerId',
                'projectId',
                'workers.lastName',
                'workers.firstName',
                'workers.email',
                'projects.name',
                'reason',
                'dateStart',
                'dateEnd'
            ])
            ->from(HistoryModel::class)
            ->where(
                self::INTERVAL_FILTER,
                $this->getBindParameters($startInterval, $endInterval)
            )
            ->join(Workers::class, 'workers.id = workerId', 'workers')
            ->join(Projects::class, 'projects.id = projectId', 'projects')
            ->getQuery()
            ->execute();
    }

    /**
     * Returns the worker's history assignments from a date interval
     * @param int $workerId
     * @param string $start
     * @param string $end
     * @return ResultsetSimple
     */
    public function getWorkerHistoryAssignments(int $workerId, string $start, string $end): ResultsetSimple
    {
        $startInterval = strtotime($start . 'T00:00:00 UTC');
        $endInterval = strtotime($end . 'T23:59:59 UTC');

        return $this->modelsManager->createBuilder()
            ->columns([
                'projectId',
                'projects.name',
                'reason',
                'dateStart',
                'dateEnd'
            ])
            ->from(HistoryModel::class)
            ->where(
                'workerId = :id: AND ' . self::INTERVAL_FILTER,
                $this->getBindParameters($startInterval, $endInterval, $workerId)
            )
            ->join(Projects::class, 'projects.id = projectId', 'projects')
            ->getQuery()
            ->execute();
    }

    /**
     * Returns the project's history assignments from a date interval
     * @param int $projectId
     * @param string $start
     * @param string $end
     * @return ResultsetSimple
     */
    public function getProjectHistoryAssignments(int $projectId, string $start, string $end): ResultsetSimple
    {
        $startInterval = strtotime($start . 'T00:00:00 UTC');
        $endInterval = strtotime($end . 'T23:59:59 UTC');

        return $this->modelsManager->createBuilder()
            ->columns([
                'workerId',
                'workers.lastName',
                'workers.firstName',
                'workers.email',
                'reason',
                'dateStart',
                'dateEnd'
            ])
            ->from(HistoryModel::class)
            ->where(
                'projectId = :id: AND ' . self::INTERVAL_FILTER,
                $this->getBindParameters($startInterval, $endInterval, $projectId)
            )
            ->join(Workers::class, 'workers.id = workerId', 'workers')
            ->getQuery()
            ->execute();
    }
}
