<?php

namespace Utils;

use PDO;
use Pixie\Connection as PixieConnection;

class DatabaseService extends \Shared\Database\DatabaseService
{

    public function connectToDb()
    {
        $connection = new PixieConnection($this->databaseConfig['driver'], [
            'host' => $this->databaseConfig['host'],
            'database' => $this->databaseConfig['database'],
            'username' => $this->databaseConfig['username'],
            'password' => $this->databaseConfig['password'],
            'port' => $this->databaseConfig['port'],
            'options' => [
                PDO::ATTR_TIMEOUT => 1,
            ],
        ]);

        $this->queryBuilder = new QueryBuilderHandler($connection, PDO::FETCH_ASSOC);
    }

}