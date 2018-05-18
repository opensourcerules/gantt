<?php

namespace GanttDashboard\App\Services;

use Phalcon\Session\Adapter\Files as SessionAdapter;

class Authentication
{
    const ACCESS_KEY = 'kanban';

    /**
     * @var SessionAdapter
     */
    private $sessionService;

    /**
     * Constructs the needed services, set in DI, for session service
     * @param SessionAdapter $sessionService
     */
    public function __construct(
        SessionAdapter $sessionService
    ) {
        $this->sessionService = $sessionService;
    }

    /**
     * If access_key matches, sets a session field
     * @param string $accessKey
     * @return bool
     */
    public function login(string $accessKey): bool
    {
        if (self::ACCESS_KEY === $accessKey) {
            $this->sessionService->set('admin', 1);

            return true;
        }

        return false;
    }

    /**
     * Removes the session variable stored by login
     * @return void
     */
    public function logout(): void
    {
        $this->sessionService->remove('admin');
    }

    /**
     * Checks if worker is logged in
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        if (null !== $this->sessionService->get('admin')) {
            return true;
        }

        return false;
    }
}
