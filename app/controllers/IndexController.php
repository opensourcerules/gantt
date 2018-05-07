<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    }

    /**
     * Renders the template after controller layout
     */
    public function initialize()
    {
        $this->view->setTemplateAfter('common');
    }
}

