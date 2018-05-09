<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->x = BASE_PATH;
    }
}
