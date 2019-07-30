<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Projects;
use GanttDashboard\App\Models\WorkersProjects;
use GanttDashboard\App\Validators\Project as ProjectValidator;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Resultset\Simple as ResultsetSimple;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Manager as ModelsManager;

class Project
{
    /**
     * @var ProjectValidator
     */
    private $projectValidator;

    /**
     * @var ModelsManager
     */
    private $modelsManager;

    /**
     * Constructs the needed service, set in DI, for validators service
     * @param ProjectValidator $projectValidator
     */
    public function __construct(
        ProjectValidator $projectValidator,
        ModelsManager $modelsManager
    ) {
        $this->projectValidator = $projectValidator;
        $this->modelsManager = $modelsManager;
    }

    /**
     * Registers the project via model in database
     * @param Projects $projectModel
     * @param array $project
     * @return ProjectValidator
     */
    public function register(Projects $projectModel, array $project): ProjectValidator
    {
        $errors = $this->projectValidator->validate($project);

        if (0 == $errors->count()) {
            $projectModel->setName($project['name']);
            $projectModel->setDescription($project['description']);
            $projectModel->create();
        }

        return $this->projectValidator;
    }

    /**
     * Gets all the projects sorted from database via model
     * @return ResultsetInterface
     */
    public function getSortedProjects(): ResultsetInterface
    {
        return Projects::find([
            'order' => 'name, description'
        ]);
    }

    /**
     * Gets all the unassigned projects sorted from database via model
     * @return ResultsetSimple
     */
    public function getUnAssignedProjects(): ResultsetSimple
    {
        return $this->modelsManager->createBuilder()
            ->columns([
                'id',
                'name',
                'description'
            ])
            ->from(Projects::class)
            ->leftJoin(WorkersProjects::class, 'id = workersProjects.projectId', 'workersProjects')
            ->where('workersProjects.projectId IS NULL')
            ->orderBy('name, description')
            ->getQuery()
            ->execute();
    }

    /**
     * Gets project from database via model
     * @param int $id
     * @return Model
     */
    public function getProject(int $id): Model
    {
        return Projects::findFirst([
            'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
    }

    /**
     * Updates the project via model in database
     * @param array $projectUpdate
     * @return ProjectValidator
     */
    public function edit(array $projectUpdate): ProjectValidator
    {
        $errors = $this->projectValidator->validate($projectUpdate);

        if (0 == $errors->count()) {
            
            /** @var $project Projects */
            $project = $this->getProject($projectUpdate['id']);
            $project->setName($projectUpdate['name']);
            $project->setDescription($projectUpdate['description']);
            $project->update();
        }

        return $this->projectValidator;
    }

    /**
     * Returns all ids of the projects from db as array of string values
     * @return array
     */
    public static function getAllProjectsIds(): array
    {
        $projects = Projects::find();
        $projectsIds = [];

        foreach ($projects as $project) {
            /** @var $project \GanttDashboard\App\Models\Projects */
            $projectsIds[] = (string)$project->getId();
        }

        return $projectsIds;
    }
}
