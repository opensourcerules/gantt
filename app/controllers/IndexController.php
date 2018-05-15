<?php

namespace GanttDashboard\App\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
    }

    /**
     * Define the notFound action
     */
    public function notFoundAction()
    {
        $this->response->setStatusCode(404, "Not Found");
        $this->response->send();
    }
}
