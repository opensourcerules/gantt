<?php

namespace GanttDashboard\App\Controllers;

class IndexController extends ControllerBase
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
