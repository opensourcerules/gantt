<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    /**
     * Define the index action
     */
    public function indexAction(): void
    {
        $this->flashSession->warning('Under construction');
    }

    /**
     * Define the notFound action
     */
    public function notFoundAction(): void
    {
        $this->response->setStatusCode(404, "Not Found");
    }
}
