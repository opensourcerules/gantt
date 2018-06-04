<?php

namespace GanttDashboard\App\Middleware;

use Phalcon\Events\Event;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Mvc\Router;
use Phalcon\Application;

/**
 * Checks if guest is authenticated and forwards him to not found page
 * if he doesn't have permission to access the requested url
 */
class Authentication
{
    /**
     * Constants to prevent a typo
     */
    const GUEST = 'guest';
    const ADMIN = 'admin';

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var SessionAdapter
     */
    private $sessionService;

    /**
     * @var AclList
     */
    private $aclList;

    /**
     * @var Router
     */
    private $router;

    /**
     * Constructs the needed services, set in DI, for
     * authentication, session service, acl and events manager
     * @param AuthenticationService $authenticationService
     * @param SessionAdapter $sessionService
     * @param AclList $aclList
     * @param Router $router
     */
    public function __construct(
        AuthenticationService $authenticationService,
        SessionAdapter $sessionService,
        AclList $aclList,
        Router $router
    ) {
        $this->authenticationService = $authenticationService;
        $this->sessionService = $sessionService;
        $this->aclList = $aclList;
        $this->router = $router;
    }

    /**
     * Checks if visitor has permissions to view the requested url
     * @param Event $event
     * @param Application $application
     * @return bool
     */
    public function beforeHandleRequest(Event $event, Application $application): bool
    {
        $role = self::GUEST;

        if (null !== $this->sessionService->get('admin')) {
            $role = self::ADMIN;
        }

        $controller = $this->router->getControllerName();
        $action     = $this->router->getActionName();
        $allowed    = $this->aclList->isAllowed($role, $controller, $action);

        if ($allowed != Acl::ALLOW) {
            $application->response->redirect(['for' => 'home']);
            $application->response->send();

            return false;
        }

        return true;
    }
}
