<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use GanttDashboard\App\Models\Workers;
use Phalcon\Http\ResponseInterface;

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
     * Initializes the worker service and authentication service
     * @return void
     */
    public function onConstruct(): void
    {
        $getDI                       = $this->getDi();
        $this->workerService         = $getDI->get(WorkerService::class);
        $this->authenticationService = $getDI->get(AuthenticationService::class);
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
        $errors = $this->workerService->register(new Workers(), $worker);

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker registration successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'registerWorker']);
        }

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
        $errors = $this->workerService->edit($worker);

        if (0 == $errors->count()) {
            $this->flashSession->success('Worker update successful');
            $this->view->disable();

            return $this->response->redirect(['for' => 'beforeEditWorker']);
        }

        $this->view->setVar('errors', $errors);
        $this->view->setVar('worker', $this->workerService->getWorker($id));
        $this->view->setVar('post', $worker);
        $view = $this->view->render('worker', 'edit');

        return $this->response->setContent($view->getContent());
    }
}
