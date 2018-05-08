<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->session->set('worker_session', 1);
    }
}
