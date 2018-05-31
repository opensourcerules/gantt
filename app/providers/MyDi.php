<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di;

class MyDi extends Di
{
    /**
     * Constructs the service providers
     */
    public function __construct()
    {
        parent::__construct();
        $this->register(new RegisterConfig());
        $this->register(new RegisterRouter());
        $this->register(new RegisterView());
        $this->register(new RegisterDispatcher());
        $this->register(new RegisterEventsManager());
        $this->register(new RegisterAclList());
        $this->register(new RegisterSession());
        $this->register(new RegisterServices());
        $this->register(new RegisterMiddlewares());
        $this->register(new RegisterFlash());
        $this->register(new RegisterUrl());
        $this->register(new RegisterDb());
        $this->register(new RegisterValidators());
        $this->register(new RegisterRequest());
        $this->register(new RegisterResponse());
        $this->register(new RegisterTag());
        $this->register(new RegisterEscaper());
        $this->register(new RegisterAnnotations());
        $this->register(new RegisterModelsManager());
        $this->register(new RegisterMetadataMemory());
        $this->register(new RegisterTransactionManager());
    }
}
