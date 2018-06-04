<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;
use \Phalcon\Http\ResponseInterface;

class IndexController extends Controller
{
    /**
     * Define the index action
     * @return ResponseInterface
     */
    public function indexAction(): ResponseInterface
    {
        $this->flashSession->warning('Under construction');
        $view = $this->view->render('Index', 'index');

        return $this->response->setContent($view->getContent());
    }

    /**
     * Define the notFound action
     * @return ResponseInterface
     */
    public function notFoundAction(): ResponseInterface
    {
        $this->response->setStatusCode(404, "Not Found");
        $view = $this->view->render('Index', 'notFOund');

        return $this->response->setContent($view->getContent());
    }
}
