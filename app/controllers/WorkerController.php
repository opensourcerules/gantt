<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use GanttDashboard\App\Services\Project as ProjectService;
use GanttDashboard\App\Models\Workers;
use Phalcon\Http\ResponseInterface;
use GanttDashboard\App\Validators\DateInterval as DateValidator;
use GanttDashboard\App\Services\History as HistoryService;

class WorkerController extends Controller
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
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var HistoryService
     */
    private $historyService;

    /**
     * @var DateValidator
     */
    private $dateValidator;

    /**
     * Initializes the services
     * @return void
     */
    public function onConstruct(): void
    {
        $getDI                       = $this->getDi();
        $this->workerService         = $getDI->get(WorkerService::class);
        $this->projectService        = $getDI->get(ProjectService::class);
        $this->authenticationService = $getDI->get(AuthenticationService::class);
        $this->historyService        = $getDI->get(HistoryService::class);
        $this->dateValidator         = $getDI->get(DateValidator::class);
    }

    /**
     * Performs the login
     * @param string $accessKey
     * @return ResponseInterface
     */
    public function loginAction(string $accessKey = ''): ResponseInterface
    {
        $this->view->disable();

        if (false === $this->authenticationService->login($accessKey)) {
            return $this->response->redirect(['for' => 'notFound'], false, 404);
        }

        $this->flashSession->success('You are now logged in as ADMIN!');

        return $this->response->redirect(['for' => 'home']);
    }

    /**
     * Performs the logout
     * @return ResponseInterface
     */
    public function logoutAction(): ResponseInterface
    {
        $this->authenticationService->logout();
        $this->flashSession->success('You are now logged out!');
        $this->view->disable();

        return $this->response->redirect(['for' => 'home']);
    }

    /**
     * If admin is logged in, registers new worker via worker service.
     * @return ResponseInterface
     */
    public function registerAction(): ResponseInterface
    {
        $worker = $this->request->getPost();
        $validator = $this->workerService->register(new Workers(), $worker);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker registration successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'registerWorker']);
        }

        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $view = $this->view->render('worker', 'register');

        return $this->response->setContent($view->getContent());
    }

    /**
     * It sends the workers to view, in order to choose the worker for edit.
     * Its route is worker/edit
     * @return ResponseInterface
     */
    public function beforeEditAction(): ResponseInterface
    {
        $this->view->setVar('workers', $this->workerService->getSortedWorkers());
        $view = $this->view->render('worker', 'beforeEdit');

        return $this->response->setContent($view->getContent());
    }

    /**
     * If admin is logged in, sends worker to view.
     * its route is worker/edit/id
     * @param int $id
     * @return ResponseInterface
     */
    public function editAction(int $id): ResponseInterface
    {
        $worker = $this->request->getPost();
        $validator = $this->workerService->edit($worker);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker update successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'beforeEditWorker']);
        }

        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $this->view->setVar('worker', $this->workerService->getWorker($id));
        $view = $this->view->render('worker', 'edit');

        return $this->response->setContent($view->getContent());
    }

    /**
     * It sends the workers to view, in order to choose the worker.
     * Its route is worker/assign
     * @return ResponseInterface
     */
    public function beforeAssignAction(): ResponseInterface
    {
        $this->view->setVar('workers', $this->workerService->getSortedWorkers());
        $view = $this->view->render('worker', 'beforeAssign');

        return $this->response->setContent($view->getContent());
    }

    /**
     * If admin is logged in, sends worker to view.
     * its route is worker/assign/id
     * @param int $id
     * @return ResponseInterface
     */
    public function assignAction(int $id): ResponseInterface
    {
        $assignments = $this->request->getPost();
        $validator = $this->workerService->assign($id, $assignments);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker assign successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'beforeAssignWorker']);
        }

        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $worker = $this->workerService->getWorker($id);
        $this->view->setVar('worker', $worker);
        $this->view->setVar('projects', $this->projectService->getSortedProjects());
        $this->view->setVar('workerProjects', $worker->getWorkersProjects());
        $this->view->setVar('workerHistory', $worker->getHistory());
        $view = $this->view->render('worker', 'assign');

        return $this->response->setContent($view->getContent());
    }

    /**
     * It sends the workers to view, in order to choose the worker.
     * Its route is worker/unassign
     * @return ResponseInterface
     */
    public function beforeUnAssignAction(): ResponseInterface
    {
        $this->view->setVar('workers', $this->workerService->getSortedAssignedWorkers());
        $view = $this->view->render('worker', 'beforeUnAssign');

        return $this->response->setContent($view->getContent());
    }

    /**
     * If admin is logged in, sends worker to view.
     * its route is worker/unassign/id
     * @param int $id
     * @return ResponseInterface
     */
    public function unAssignAction(int $id): ResponseInterface
    {
        $unAssignments = $this->request->getPost();
        $validator = $this->workerService->unAssign($id, $unAssignments);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker unassign successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'beforeUnAssignWorker']);
        }

        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $worker = $this->workerService->getWorker($id);
        $this->view->setVar('worker', $worker);
        $this->view->setVar('projects', $worker->getProjects());
        $this->view->setVar('workerHistory', $worker->getHistory());
        $view = $this->view->render('worker', 'unAssign');

        return $this->response->setContent($view->getContent());
    }

    /**
     * It sends the workers to view, in order to choose the worker for showing its history.
     * Its route is worker/history
     * @return ResponseInterface
     */
    public function beforeHistoryAction(): ResponseInterface
    {
        $this->view->setVar('workers', $this->workerService->getSortedWorkers());
        $view = $this->view->render('worker', 'beforeHistory');

        return $this->response->setContent($view->getContent());
    }

    /**
     * Defines the history action.
     * its route is worker/history/id
     * @param int $id
     * @return ResponseInterface
     */
    public function historyAction(int $id): ResponseInterface
    {
        $dateInterval = $this->request->getPost();
        $validator = $this->dateValidator->validateDateInterval($dateInterval);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $historyAssignments = $this->historyService->getWorkerHistoryAssignments(
                $id,
                $dateInterval['start'],
                $dateInterval['end']
            );
            $this->view->setVar('historyAssignments', $historyAssignments);
        }

        $this->view->setVar('worker', $this->workerService->getWorker($id));
        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $view = $this->view->render('worker', 'history');

        return $this->response->setContent($view->getContent());
    }
}
