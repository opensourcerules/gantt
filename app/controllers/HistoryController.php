<?php

namespace GanttDashboard\App\Controllers;

use GanttDashboard\App\Services\History as HistoryService;
use Phalcon\Mvc\Controller;
use Phalcon\Http\ResponseInterface;
use GanttDashboard\App\Validators\DateInterval as DateValidator;

class HistoryController extends Controller
{
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
        $this->historyService        = $getDI->get(HistoryService::class);
        $this->dateValidator         = $getDI->get(DateValidator::class);
    }

    /**
     * Define the index action
     * @return ResponseInterface
     */
    public function indexAction(): ResponseInterface
    {
        $dateInterval = $this->request->getPost();
        $validator = $this->dateValidator->validateDateInterval($dateInterval);
        $errors = $validator->getMessages();

        if (0 == $errors->count()) {
            $historyAssignments = $this->historyService->getHistoryAssignments(
                $dateInterval['start'],
                $dateInterval['end']
            );
            $this->view->setVar('historyAssignments', $historyAssignments);
        }

        $this->view->setVar('hasErrors', $validator->hasErrors());
        $this->view->setVar('errors', $errors);
        $view = $this->view->render('history', 'index');

        return $this->response->setContent($view->getContent());
    }
}
