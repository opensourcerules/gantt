<?php

namespace GanttDashboard\App\Middleware;

use Phalcon\Application;
use Phalcon\Events\Event;
use GanttDashboard\App\Services\Authentication as AuthenticationService;

/**
 * Redirect
 *
 * Checks the request and redirects the user somewhere else if needed
 */
class Redirect
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * Constructs the needed services, set in DI, for authentication service
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        AuthenticationService $authenticationService
    ) {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Before anything happens
     *
     * @param Event $event
     * @param Application $application
     *
     * @return bool
     */
    public function beforeSendResponse(Event $event, Application $application)
    {
        $restrictedUri = [
            '/worker/register',
            '/worker/edit',
        ];

        if (true === in_array($application->request->getURI(), $restrictedUri) &&
            false === $this->authenticationService->isLoggedIn()) {
            $application->response->redirect(['for' => 'home']);
            $application->response->send();

            return false;
        }

        return true;
    }
}
