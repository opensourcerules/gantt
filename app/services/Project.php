<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Projects;
use GanttDashboard\App\Validators\Project as ProjectValidator;
use Phalcon\MVC\Model\ResultsetInterface;
use Phalcon\Validation\Message\Group as MessageGroup;
use Phalcon\Mvc\Model;

class Project
{
    /**
     * @var ProjectValidator
     */
    private $projectValidator;

    /**
     * Constructs the needed service, set in DI, for validators service
     * @param ProjectValidator $projectValidator
     */
    public function __construct(
        ProjectValidator $projectValidator
    ) {
        $this->projectValidator = $projectValidator;
    }

    /**
     * Registers the project via model in database
     * @param Projects $projectModel
     * @param array $project
     * @return MessageGroup
     */
    public function register(Projects $projectModel, array $project): MessageGroup
    {
        $errors = $this->projectValidator->validate($project);

        if (0 == $errors->count()) {
            $projectModel->setName($project['name']);
            $projectModel->setDescription($project['description']);
            $projectModel->create();
        }

        return $errors;
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
     * @return MessageGroup
     */
    public function edit(array $projectUpdate): MessageGroup
    {
        $errors = $this->projectValidator->validate($projectUpdate);

        if (0 == $errors->count()) {
            
            /** @var $project Projects */
            $project = $this->getProject($projectUpdate['id']);
            $project->setName($projectUpdate['name']);
            $project->setDescription($projectUpdate['description']);
            $project->update();
        }

        return $errors;
    }
}
