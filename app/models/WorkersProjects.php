<?php

namespace GanttDashboard\App\Models;

use Phalcon\Mvc\Model;

class WorkersProjects extends Model
{
    /**
     *
     * @var integer
     * @Column(column="worker_id", type="integer", length=10, nullable=false)
     */
    protected $workerId;

    /**
     *
     * @var integer
     * @Column(column="project_id", type="integer", length=10, nullable=false)
     */
    protected $projectId;

    /**
     * Initializes the relations between tables
     */
    public function initialize()
    {
        $this->belongsTo(
            'worker_id',
            Workers::class,
            'id',
            ['alias' => 'Workers']
        );

        $this->belongsTo(
            'project_id',
            Projects::class,
            'id',
            ['alias' => 'Projects']
        );
    }

    /**
     * Method to set the value of field workerId
     *
     * @param string $workerId
     * @return $this
     */
    public function setWorkerId($workerId): WorkersProjects
    {
        $this->workerId = $workerId;

        return $this;
    }

    /**
     * Method to set the value of field projectId
     *
     * @param string $projectId
     * @return $this
     */
    public function setProjectId($projectId): WorkersProjects
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Returns the value of field workerId
     *
     * @return integer
     */
    public function getWorkerId(): int
    {
        return $this->workerId;
    }

    /**
     * Returns the value of field projectId
     *
     * @return integer
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap(): array
    {
        return [
            'worker_id'  => 'workerId',
            'project_id' => 'projectId'
        ];
    }
}
