<?php

namespace Shared\Database;

use PDO;
use Pixie\Connection as PixieConnection;
use Pixie\Exception;
use Pixie\QueryBuilder\QueryBuilderHandler;

class DatabaseService
{
    protected QueryBuilderHandler $queryBuilder;

    protected int $retries = 0;

    protected array $databaseConfig;

    public function __construct(array $databaseConfig)
    {
        $this->databaseConfig = $databaseConfig;
    }

    /**
     * @throws Exception
     */
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

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @param string $table
     * @return QueryBuilderHandler
     * @throws Exception
     */
    public function getTable(string $table): QueryBuilderHandler
    {
        return $this->queryBuilder->table($table);
    }

    public function close(): void
    {
        if ($this->queryBuilder) {
            $pdo = $this->queryBuilder->pdo();
            $pdo = null;
        }
    }

}