<?php

namespace Utils;

require 'config/Config.php';

use Config;
use Pixie\QueryBuilder\QueryBuilderHandler;

class FixtureLoader
{

    public static DatabaseService $databaseService;

    public static array $modelsModified;

    public static array $modelsCharged;

    private static array $fixtures;

    private static array $modulesLoaded = [
        'Trainer' => false,
        'Gym' => false,
        'GymTrainer' => false,
    ];

    public static function preSuite(array $fixtures, string $module)
    {
        self::$fixtures = $fixtures;

        $config = Config::getConfig();

        self::$databaseService = new DatabaseService($config['databaseConfig']);

        self::initDB($config['databaseConfig'], $module);

        self::$databaseService->connectToDb();

        self::load($fixtures);
    }

    private static function initDB(array $databaseConfig, string $module)
    {
        if (!self::$modulesLoaded[$module]) {
            shell_exec("mysql -u {$databaseConfig['username']} -p{$databaseConfig['password']} -h {$databaseConfig['host']} -P {$databaseConfig['port']} -e 'DROP DATABASE IF EXISTS {$databaseConfig['database']}; CREATE DATABASE {$databaseConfig['database']};'");

            shell_exec("mysql -u {$databaseConfig['username']} -p{$databaseConfig['password']} -h {$databaseConfig['host']} -P {$databaseConfig['port']} {$databaseConfig['database']} < " .
                __DIR__ .
                '/dump.sql');
            self::$modelsModified = self::$modelsCharged = [];
            self::$modulesLoaded[$module] = true;
        }
    }

    public static function load(?array $fixtures)
    {
        foreach ($fixtures ?? self::$fixtures as $table => $rows) {
            if (!empty($rows['depends'])) {
                self::load(array_diff_key(array_intersect_key($fixtures ?? self::$fixtures, array_flip($rows['depends'])), self::$modelsCharged));
                unset($rows['depends']);
            }

            if (!isset(self::$modelsCharged[$table])) {
                foreach ($rows as $row) {
                    self::$databaseService->getQueryBuilder()->pdo()->query('SET FOREIGN_KEY_CHECKS = 0;');
                    self::$databaseService->getTable($table)->insert($row);
                    self::$databaseService->getQueryBuilder()->pdo()->query('SET FOREIGN_KEY_CHECKS = 1;');
                }
                self::$modelsCharged[$table] = true;
            }
        }

    }

    public static function reload()
    {
        if (empty(self::$modelsModified)) {
            return;
        }

        $truncates = '';

        foreach (array_keys(self::$modelsModified) as $table) {
            $truncates .= "TRUNCATE TABLE {$table}; ";
            unset(self::$modelsCharged[$table]);
        }

        self::$databaseService->getQueryBuilder()->pdo()->query("SET FOREIGN_KEY_CHECKS = 0; {$truncates} SET FOREIGN_KEY_CHECKS = 1;")->closeCursor();

        self::$databaseService->getQueryBuilder()->removeEvent('after-insert');
    }

    public static function postLoad()
    {
        self::$databaseService->getQueryBuilder()->registerEvent('after-insert',
            ':any',
            fn(QueryBuilderHandler $queryBuilder) => self::$modelsModified[$queryBuilder->getStatements()['tables'][0]] = true);

        self::$databaseService->getQueryBuilder()->registerEvent('after-update',
            ':any',
            fn(QueryBuilderHandler $queryBuilder) => self::$modelsModified[$queryBuilder->getStatements()['tables'][0]] = true);

        self::$databaseService->getQueryBuilder()->registerEvent('after-delete',
            ':any',
            fn(QueryBuilderHandler $queryBuilder) => self::$modelsModified[$queryBuilder->getStatements()['tables'][0]] = true);
    }

}