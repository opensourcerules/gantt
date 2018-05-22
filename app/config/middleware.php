<?php

use GanttDashboard\App\Middleware\Redirect as RedirectMiddleware;

$events = $di->getShared('eventsManager');
$events->attach('application', $di->getShared(RedirectMiddleware::class));
