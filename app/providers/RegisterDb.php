<?php

namespace GanttDashboard\App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

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

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
            $params = [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'charset'  => $config->database->charset
            ];

            if ($config->database->adapter == 'Postgresql') {
                unset($params['charset']);
            }

            $connection = new $class($params);

            return $connection;
        });
    }
}
