<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;
use GanttDashboard\App\Models\Workers as WorkerModel;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use Phalcon\Http\Response;
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
     * @var WorkerModel
     */
    private $workerModel;

    /**
     * Initializes the worker service, authentication service and worker model properties
     * @return void
     */
    public function onConstruct(): void
    {
        $getDI                         = $this->getDi();
        $this->workerService           = $getDI->get(WorkerService::class);
        $this->authenticationService   = $getDI->get(AuthenticationService::class);
        $this->workerModel             = $getDI->get(WorkerModel::class);
    }

    /**
     * Performs the login
     * @param string $accessKey
     * @return Response|ResponseInterface
     */
    public function loginAction(string $accessKey = ''): object
    {
        if (false === $this->authenticationService->login($accessKey)) {
            $this->view->disable();

            return $this->response->redirect(['for' => 'notFound'], false, 404);
        }

        $this->flashSession->success('You are now logged in as ADMIN!');
        $this->view->disable();

        return $this->response->redirect(['for' => 'home']);
    }

    /**
     * Performs the logout
     * @return Response|ResponseInterface
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
        if (false === $this->authenticationService->isLoggedIn()) {
            $this->view->disable();
            $this->response->redirect(['for' => 'home']);
        }

        $worker = $this->request->getPost();

        /**
         * If submit
         */
        if (false === empty($worker)) {
            $errors = $this->workerService->register($worker);

            if (0 == $errors->count()) {
                $this->flashSession->success('Worker registration successful');
                $this->view->disable();
                $this->response->redirect(['for' => 'registerWorker']);
            }

            $this->view->errors = $errors;
        }
    }
}
