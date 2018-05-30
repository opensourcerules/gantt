<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use GanttDashboard\App\Models\Workers;

class WorkerController extends Controller
{
    /**
     * @var WorkerService
     */
    private $workerService;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * Initializes the worker service, authentication service and worker model properties
     * @return void
     */
    public function onConstruct(): void
    {
        $getDI                         = $this->getDi();
        $this->workerService           = $getDI->get(WorkerService::class);
        $this->authenticationService   = $getDI->get(AuthenticationService::class);
    }

    /**
     * Performs the login
     * @param string $accessKey
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginAction(string $accessKey = ''): object
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
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function logoutAction(): object
    {
        $this->authenticationService->logout();
        $this->flashSession->success('You are now logged out!');
        $this->view->disable();

        return $this->response->redirect(['for' => 'home']);
    }

    /**
     * If admin is logged in, registers new worker via worker service.
     */
    public function registerAction(): void
    {
        $worker = $this->request->getPost();
        $errors = $this->workerService->register(new Workers(), $worker);

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker registration successful');
            $this->view->disable();
            $this->response->redirect(['for' => 'registerWorker']);
        }

        $this->view->setVar('errors', $errors);
    }

    /**
     * It sends the workers to view, in order to choose the worker for edit.
     * Its route is worker/edit
     */
    public function beforeEditAction(): void
    {
        $this->view->setVar('workers', $this->workerService->getSortedWorkers());
    }

    /**
     * If admin is logged in, sends worker to view.
     * its route is worker/edit/id
     * @param int $id
     */
    public function editAction(int $id): void
    {
        $worker = $this->request->getPost();
        $errors = $this->workerService->edit($worker);

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker update successful');
            $this->view->disable();
            $this->response->redirect(['for' => 'beforeEditWorker']);
        }

        $this->view->setVar('errors', $errors);
        $this->view->setVar('worker', $this->workerService->getWorker($id));
        $this->view->setVar('post', $worker);
    }
}
