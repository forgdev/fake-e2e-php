<?php

use Config\CurlConfig;
use Psr\Container\ContainerInterface;
use Shared\Database\DatabaseService;

return array_merge(Config::getConfig(), [
    DatabaseService::class => function (ContainerInterface $container) {
        $databaseService = new DatabaseService($container->get('databaseConfig'));
        $databaseService->connectToDb();

        return $databaseService;
    },
    CurlConfig::class => function (ContainerInterface $container) {
        return new CurlConfig($container->get('curlConfig'));
    },
]);
