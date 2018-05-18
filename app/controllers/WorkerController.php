<?php

namespace GanttDashboard\App\Controllers;

use GanttDashboard\App\Validators\Worker as WorkerValidator;
use GanttDashboard\App\Models\Workers as WorkerModel;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

class WorkerController extends ControllerBase
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
     * @var WorkerValidator
     */
    private $workerValidator;

    /**
     * @var WorkerModel
     */
    private $workerModel;

    /**
     * Initialises the workerService, workerValidator and workerModel properties
     * @return void
     */
    public function onConstruct(): void
    {
        $getDI                 = $this->getDi();
        $this->workerService   = $getDI->get(WorkerService::class);
        $this->authenticationService   = $getDI->get(AuthenticationService::class);
        $this->workerValidator = $getDI->get(WorkerValidator::class);
        $this->workerModel     = $getDI->get(WorkerModel::class);
    }

    /**
     * Performs the login
     * @param string $accessKey
     * @return Response|ResponseInterface
     */
    public function loginAction(string $accessKey = ''): object
    {
        if (false === $this->authenticationService->login($accessKey)) {
            return $this->redirectTo(['for' => 'notFound'], 404);
        }

        $this->flashSession->success('You are now logged in as ADMIN!');

        return $this->redirectTo(['for' => 'home']);
    }

    /**
     * Performs the logout
     * @return Response|ResponseInterface
     */
    public function logoutAction(): object
    {
        $this->authenticationService->logout();
        $this->flashSession->success('You are now logged out!');

        return $this->redirectTo(['for' => 'home']);
    }

    /**
     * If admin is logged in, registers new worker via worker service.
     */
    public function registerAction(): void
    {
        if (false === $this->authenticationService->isLoggedIn()) {
            $this->redirectTo(['for' => 'home'], 403);
        }

        $worker = $this->request->getPost();

        if (false === empty($worker)) {
            $errors = $this->workerValidator->validation($worker);

            if (0 === count($errors) && true === $this->workerService->register($worker)) {
                $this->flashSession->success('Worker registration successful');
                $this->redirectTo(['for' => 'registerWorker']);
            }

            $this->view->errors = $errors;
        }
    }
}
