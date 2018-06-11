<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\History as HistoryModel;

class History
{
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
        $historyModel->setReason('{"assign":"' . $reason . '"}');

        $historyModel->create();
    }
}
