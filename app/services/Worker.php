<?php

namespace GanttDashboard\App\Services;

use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Validators\Worker as WorkerValidator;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\View;

class Worker extends Base
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var WorkerValidator
     */
    private $workerValidator;

    /**
     * @var Workers
     */
    private $workerModel;

    /**
     * Constructs the needed services, set in DI, for session, security, model,
     * flashSession and view
     * @param Authentication $authenticationService
     * @param WorkerValidator $workerValidator
     * @param Workers $workerModel
     * @param FlashSession $flashSession
     * @param View $view
     */
    public function __construct(
        AuthenticationService $authenticationService,
        WorkerValidator $workerValidator,
        Workers $workerModel,
        FlashSession $flashSession,
        View $view
    ) {
        parent::__construct($flashSession, $view);
        $this->authenticationService = $authenticationService;
        $this->workerValidator = $workerValidator;
        $this->workerModel = $workerModel;
    }

    /**
     * If $worker array was submitted and validation is ok, registers the worker
     * @param array $worker
     * @return bool
     */
    public function register(array $worker) : bool
    {
        if (true === empty($worker)) {
            return false;
        }

        $errors = $this->workerValidator->validation($worker);
        $this->view->errors = $errors;

        if (0 === count($errors)) {
            if ('1' === $worker['admin']) {
                $worker['password'] = $this->authenticationService->hashPassword($worker['password']);
            }

            $this->workerModel->setLastName($worker['lastName']);
            $this->workerModel->setFirstName($worker['firstName']);
            $this->workerModel->setEmail($worker['email']);
            $this->workerModel->setPassword($worker['password']);
            $this->workerModel->setAdmin($worker['admin']);
            $created = $this->workerModel->create();

            if (true === $created) {
                $this->flashSession->success('Worker registration successful');
            }

            return $created;
        }

        return false;
    }
}
