<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;
use GanttDashboard\App\Services\Project as ProjectService;
use GanttDashboard\App\Models\Projects;
use Phalcon\Http\ResponseInterface;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    private $projectService;

    /**
     * Initializes the project service
     * @return void
     */
    public function onConstruct(): void
    {
        $this->projectService = $this->getDi()->get(ProjectService::class);
    }

    /**
     * If admin is logged in, registers new project via project service.
     * @return ResponseInterface
     */
    public function registerAction(): ResponseInterface
    {
        $project = $this->request->getPost();
        $errors = $this->projectService->register(new Projects(), $project);

        if (0 == $errors->count()) {
            $this->flashSession->success('Project registration successful');
            $this->view->disable();
            
            return $this->response->redirect(['for' => 'registerProject']);
        }

        $this->view->setVar('hasErrors', $this->projectService->hasErrors($errors));
        $this->view->setVar('errors', $errors);
        $view = $this->view->render('project', 'register');

        return $this->response->setContent($view->getContent());
    }

    /**
     * It sends the projects to view, in order to choose the project for edit.
     * Its route is project/edit
     * @return ResponseInterface
     */
    public function beforeEditAction(): ResponseInterface
    {
        $this->view->setVar('projects', $this->projectService->getSortedProjects());
        $view = $this->view->render('Project', 'beforeEdit');

        return $this->response->setContent($view->getContent());
    }

    /**
     * If admin is logged in, sends project to view.
     * its route is project/edit/id
     * @param int $id
     * @return ResponseInterface
     */
    public function editAction(int $id): ResponseInterface
    {
        $project = $this->request->getPost();
        $errors = $this->projectService->edit($project);

        if (0 == $errors->count()) {
            $this->flashSession->success('Project update successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'beforeEditProject']);
        }

        $this->view->setVar('hasErrors', $this->projectService->hasErrors($errors));
        $this->view->setVar('errors', $errors);
        $this->view->setVar('project', $this->projectService->getProject($id));
        $view = $this->view->render('project', 'edit');

        return $this->response->setContent($view->getContent());
    }
}
