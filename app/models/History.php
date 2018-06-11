<?php

namespace GanttDashboard\App\Models;

use Phalcon\Mvc\Model;

class History extends Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=10, nullable=false)
     */
    protected $id;

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
     *
     * @var integer
     * @Column(column="date_start", type="integer", length=10, nullable=true)
     */
    protected $dateStart;

    /**
     *
     * @var integer
     * @Column(column="date_end", type="integer", length=10, nullable=true)
     */
    protected $dateEnd;

    /**
     *
     * @var string
     * @Column(column="reason", type="string", length=255, nullable=true)
     */
    protected $reason;

    /**
     * Initializes the relations between tables
     */
    public function initialize()
    {
        $this->belongsTo(
            'workerId',
            'Workers',
            'id',
            ['alias' => 'Workers']
        );

        $this->belongsTo(
            'projectId',
            'Projects',
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
    public function setWorkerId($workerId): History
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
    public function setProjectId($projectId): History
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Method to set the value of field dateStart
     *
     * @param string $dateStart
     * @return $this
     */
    public function setDateStart($dateStart): History
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Method to set the value of field dateEnd
     *
     * @param string $dateEnd
     * @return $this
     */
    public function setDateEnd($dateEnd): History
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Method to set the value of field reason
     *
     * @param string $reason
     * @return $this
     */
    public function setReason($reason): History
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
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
     * Returns the value of field dateStart
     *
     * @return integer | null
     */
    public function getDateStart(): ?int
    {
        return $this->dateStart;
    }

    /**
     * Returns the value of field dateEnd
     *
     * @return integer | null
     */
    public function getDateEnd(): ?int
    {
        return $this->dateEnd;
    }

    /**
     * Returns the value of field reason
     *
     * @return string | null
     */
    public function getReason(): ?string
    {
        return $this->reason;
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
            'id'         => 'id',
            'worker_id'  => 'workerId',
            'project_id' => 'projectId',
            'date_start' => 'dateStart',
            'date_end'   => 'dateEnd',
            'reason'     => 'reason'
        ];
    }
}
