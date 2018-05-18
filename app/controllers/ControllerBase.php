<?php

namespace GanttDashboard\App\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    /**
     * Redirect by HTTP to another action or URL
     *
     * <code>
     * // Using a string redirect (internal/external)
     * $this->redirect('posts/index');
     * $this->redirect("http://www.example.com/new-location", 301, true);
     *
     * // Making a redirection based on a named route
     * $this->redirect([
     *         'for'        => 'index-lang',
     *         'lang'       => 'jp',
     *         'controller' => 'index'
     * ]);
     * </code>
     *
     * @param mixed $location
     * @param int $statusCode
     * @param bool $externalRedirect
     * @return Response
     */
    public function redirectTo($location = null, $statusCode = 200, $externalRedirect = false) : object
    {
        return $this->response->redirect($location, $externalRedirect, $statusCode);
    }
}
