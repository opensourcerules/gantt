<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Phalcon\Db\Adapter\Pdo\Factory;

class RegisterDb implements ServiceProviderInterface
{
    /**
     * Database connection is created based on the parameters defined in the configuration file
     * @param DiInterface $di
     * @return void
     */
    public function register(DiInterface $di): void
    {
        $di->setShared('db', function () use ($di) {
            $config = $di->get('config');

            $params = [
                'adapter'  => $config->database->adapter,
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'charset'  => $config->database->charset
            ];

            if ($config->database->adapter == 'Postgresql') {
                unset($params['charset']);
            }

            $connection = Factory::load($params);

            return $connection;
        });
    }
}
