<?php

namespace GanttDashboard\App\Models;

use Phalcon\Mvc\Model;

class Projects extends Model
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
     * @var string
     * @Column(column="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(column="description", type="string", length=255, nullable=false)
     */
    protected $description;

    /**
     * Initializes the relations between tables
     */
    public function initialize()
    {
        $this->hasMany(
            'id',
            WorkersProjects::class,
            'projectId',
            ['alias' => 'WorkersProjects']
        );

        $this->hasManyToMany(
            'id',
            WorkersProjects::class,
            'projectId',
            'workerId',
            Workers::class,
            'id',
            ['alias' => 'Workers']
        );

        $this->hasMany(
            'id',
            History::class,
            'projectId',
            ['alias' => 'History']
        );
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name): Projects
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description): Projects
    {
        $this->description = $description;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
            'id'          => 'id',
            'name'        => 'name',
            'description' => 'description'
        ];
    }
}
