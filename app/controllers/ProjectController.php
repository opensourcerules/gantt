<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;
use GanttDashboard\App\Services\Project as ProjectService;
use GanttDashboard\App\Models\Projects;
use Phalcon\Http\ResponseInterface;
use GanttDashboard\App\Validators\DateInterval as DateValidator;
use GanttDashboard\App\Services\History as HistoryService;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    private $projectService;

    /**
     * @var HistoryService
     */
    private $historyService;

    /**
     * @var DateValidator
     */
    private $dateValidator;

    /**
     * Initializes the project service
     * @return void
     */
    public function onConstruct(): void
    {
        $getDI                       = $this->getDi();
        $this->projectService        = $getDI->get(ProjectService::class);
        $this->historyService        = $getDI->get(HistoryService::class);
        $this->dateValidator         = $getDI->get(DateValidator::class);
    }

    /**
     * If admin is logged in, registers new project via project service.
     * @return ResponseInterface
     */
    public function registerAction(): ResponseInterface
    {
        $project = $this->request->getPost();
        $validator = $this->projectService->register(new Projects(), $project);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $this->flashSession->success('Project registration successful');
            $this->view->disable();
            
            return $this->response->redirect(['for' => 'registerProject']);
        }

        $this->view->setVar('hasErrors', $validator->hasErrors());
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
        $validator = $this->projectService->edit($project);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $this->flashSession->success('Project update successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'beforeEditProject']);
        }

        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $this->view->setVar('project', $this->projectService->getProject($id));
        $view = $this->view->render('project', 'edit');

        return $this->response->setContent($view->getContent());
    }

    /**
     * It sends the projects to view, in order to choose the project for showing its history.
     * Its route is project/history
     * @return ResponseInterface
     */
    public function beforeHistoryAction(): ResponseInterface
    {
        $this->view->setVar('projects', $this->projectService->getSortedProjects());
        $view = $this->view->render('Project', 'beforeHistory');

        return $this->response->setContent($view->getContent());
    }

    /**
     * Defines the history action.
     * its route is project/history/id
     * @param int $id
     * @return ResponseInterface
     */
    public function historyAction(int $id): ResponseInterface
    {
        $dateInterval = $this->request->getPost();
        $validator = $this->dateValidator->validateDateInterval($dateInterval);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $historyAssignments = $this->historyService->getProjectHistoryAssignments(
                $id,
                $dateInterval['start'],
                $dateInterval['end']
            );
            $this->view->setVar('historyAssignments', $historyAssignments);
        }

        $this->view->setVar('project', $this->projectService->getProject($id));
        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $view = $this->view->render('project', 'history');

        return $this->response->setContent($view->getContent());
    }
}
