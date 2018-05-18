<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction(): void
    {
    }

    /**
     * Define the notFound action
     */
    public function notFoundAction(): void
    {
        $this->response->setStatusCode(404, "Not Found");
        $this->response->send();
    }
}
