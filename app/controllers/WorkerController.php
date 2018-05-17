<?php

namespace GanttDashboard\App\Controllers;

use GanttDashboard\App\Validators\Traits;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use GanttDashboard\App\Models\Workers as WorkerModel;
use GanttDashboard\App\Services\Worker as WorkerService;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

class WorkerController extends ControllerBase
{
    use Traits;

    /**
     * @var WorkerService
     */
    private $workerService;

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
     */
    public function onConstruct()
    {
        $getDI                 = $this->getDi();
        $this->workerService   = $getDI->get(WorkerService::class);
        $this->workerValidator = $getDI->get(WorkerValidator::class);
        $this->workerModel     = $getDI->get(WorkerModel::class);
    }

    /**
     * Performs the login
     * @param string $accessKey
     * @return Response|ResponseInterface
     */
    public function loginAction(string $accessKey = '')
    {
        if (false === $this->workerService->login($accessKey)) {
            return $this->response->redirect(['for' => 'notFound'], false, 404);
        }

        $this->flashSession->success('You are now logged in as ADMIN!');

        return $this->response->redirect(['for' => 'home'], false, 200);
    }

    /**
     * Performs the logout
     * @return Response|ResponseInterface
     */
    public function logoutAction()
    {
        $this->workerService->logout();
        $this->flashSession->success('You are now logged out!');

        return $this->response->redirect(['for' => 'home'], false, 200);
    }

    /**
     * If Admin is logged in, registers new worker by calling validation and if ok,
     * calls Workers model's register method
     */
    public function registerAction()
    {
        if (false === $this->workerService->isLoggedIn()) {
            $this->response->redirect(['for' => 'home'], false, 403);
        }

        if (null !== $this->request->getPost('submit')) {
            $worker = $this->request->getPost();
            $messages = $this->workerValidator->validate($worker);

            if (0 === $messages->count()) {
                $worker['password'] = $this->workerService->hashPassword($worker['password']);

                if (true === $this->workerModel->register($worker)) {
                    $this->flashSession->success('Worker registration successful');
                    $this->response->redirect(['for' => 'registerWorker'], false, 200);
                }
            }

            $this->view->notices = $this->buildNoticesForView($messages);
        }
    }
}
