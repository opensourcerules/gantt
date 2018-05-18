<?php

namespace GanttDashboard\App\Services;

use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\View;

class Base
{
    /**
     * @var FlashSession
     */
    protected $flashSession;

    /**
     * @var View
     */
    protected $view;

    /**
     * Constructs the needed services, set in DI, for flashSession and view
     * @param FlashSession $flashSession
     * @param View $view
     */
    public function __construct(FlashSession $flashSession, View $view)
    {
        $this->flashSession = $flashSession;
        $this->view = $view;
    }
}
