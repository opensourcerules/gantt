<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;

class RegisterAclList implements ServiceProviderInterface
{
    /**
     * Constants to prevent a typo
     */
    const GUEST = 'guest';
    const ADMIN = 'admin';

    /**
     * Register access control list
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('acl', function () {
            $acl = new AclList();
            $acl->setDefaultAction(Acl::DENY);

            $acl->addRole(new Role(self::GUEST));
            $acl->addRole(new Role(self::ADMIN));

            $acl->addResource(new Resource('Worker'), [
                'login',
                'beforeHistory',
                'history'
            ]);
            $acl->addResource(new Resource('Project'), [
                'beforeHistory',
                'history'
            ]);
            $acl->addResource(new Resource('History'), [
                'index'
            ]);
            $acl->addResource(new Resource('Index'), [
                'index',
                'notFound',
            ]);

            $acl->allow(self::GUEST, 'Worker', 'login');
            $acl->allow(self::GUEST, 'Worker', 'beforeHistory');
            $acl->allow(self::GUEST, 'Worker', 'history');
            $acl->allow(self::GUEST, 'Project', 'beforeHistory');
            $acl->allow(self::GUEST, 'Project', 'history');
            $acl->allow(self::GUEST, 'History', 'index');
            $acl->allow(self::GUEST, 'Index', 'index');
            $acl->allow(self::GUEST, 'Index', 'notFound');

            $acl->allow(self::ADMIN, '*', '*');

            return $acl;
        });
    }
}
