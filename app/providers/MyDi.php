<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Annotations\Adapter\Memory;
use Phalcon\Di;
use Phalcon\Escaper;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\MetaData\Memory as MetaDataMemory;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class MyDi extends Di
{
    /**
     * Registers the service providers
     * @return void
     */
    public function initialize(): void
    {
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
        $this->setShared('request', Request::class);
        $this->setShared('response', Response::class);
        $this->setShared('tag', Tag::class);
        $this->setShared('escaper', Escaper::class);
        $this->setShared('annotations', Memory::class);
        $this->setShared('modelsManager', Manager::class);
        $this->setShared('modelsMetadata', MetaDataMemory::class);
        $this->setShared('transactionManager', TransactionManager::class);
    }
}
