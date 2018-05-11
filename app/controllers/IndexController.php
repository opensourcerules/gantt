<?php

namespace GanttDashboard\App\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
    }

    /**
     * Define the notFound404 action
     */
    public function notFound404Action()
    {
        $this->response->setStatusCode(404, "Not Found");
        $this->response->send();
    }
}
