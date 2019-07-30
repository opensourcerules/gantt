<?php

namespace GanttDashboard\App\Controllers;

use GanttDashboard\App\Services\History as HistoryService;
use GanttDashboard\App\Services\Project as ProjectService;
use GanttDashboard\App\Services\Worker as WorkerService;
use Phalcon\Mvc\Controller;
use Phalcon\Http\ResponseInterface;

class IndexController extends Controller
{
    /**
     * @var WorkerService
     */
    private $workerService;

    /**
     * @var ProjectService
     */
    private $projectService;

    /**
     * @var HistoryService
     */
    private $historyService;

    /**
     * Initializes the services
     * @return void
     */
    public function onConstruct(): void
    {
        $getDI                       = $this->getDi();
        $this->workerService         = $getDI->get(WorkerService::class);
        $this->projectService        = $getDI->get(ProjectService::class);
        $this->historyService        = $getDI->get(HistoryService::class);
    }

    /**
     * Define the index action
     * @return ResponseInterface
     */
    public function indexAction(): ResponseInterface
    {
        $this->view->setVar('assignments', $this->historyService->getActualAssignments());
        $this->view->setVar('unAssignedWorkers', $this->workerService->getUnAssignedWorkers());
        $this->view->setVar('unAssignedProjects', $this->projectService->getUnAssignedProjects());
        $view = $this->view->render('index', 'index');

        return $this->response->setContent($view->getContent());
    }

    /**
     * Define the notFound action
     * @return ResponseInterface
     */
    public function notFoundAction(): ResponseInterface
    {
        $this->response->setStatusCode(404, "Not Found");
        $view = $this->view->render('index', 'notFound');

        return $this->response->setContent($view->getContent());
    }
}
