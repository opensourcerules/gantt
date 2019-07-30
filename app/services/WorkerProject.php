<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\WorkersProjects;

class WorkerProject
{
    /**
     * Inserts the $workerId - $projectId pair into db via model
     * @param int $workerId
     * @param int $projectId
     * @return bool
     */
    public function add(int $workerId, int $projectId): bool
    {
        $workersProjects = new WorkersProjects();
        $workersProjects->setWorkerId($workerId);
        $workersProjects->setProjectId($projectId);

        return $workersProjects->create();
    }

    /**
     * Deletes the $workerId - $projectId pair into db via model
     * @param int $workerId
     * @param int $projectId
     * @return bool
     */
    public function remove(int $workerId, int $projectId): bool
    {
        $workerProject = WorkersProjects::find([
            'conditions' => 'workerId = ?1 and projectId = ?2',
            'bind'       => [
                1 => $workerId,
                2 => $projectId
            ]
        ]);

        /** @var $workerProject WorkersProjects */

        return $workerProject->delete();
    }
}
