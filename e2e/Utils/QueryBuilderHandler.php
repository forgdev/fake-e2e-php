<?php


namespace Utils;


class QueryBuilderHandler extends \Pixie\QueryBuilder\QueryBuilderHandler
{

    public function query($sql, $bindings = [])
    {
        ['action' => $action, 'table' => $table] = $this->getSqlInfo($sql);

        if ($action == 'UPDATE') {
            $this->statements = [
                'tables' => [
                    $table,
                ],
            ];
            $this->connection->getEventHandler()->fireEvents($this, 'after-update');
        }

        if ($action == 'INSERT' || $action == 'INSERT IGNORE') {
            $this->statements = [
                'tables' => [
                    $table,
                ],
            ];

            $this->connection->getEventHandler()->fireEvents($this, 'after-insert');
        }

        return parent::query($sql, $bindings);
    }

    private function getSqlInfo(string $sql)
    {
        $update = '/^(?<action>.*) (?<table>.*) SET/';
        $insert = '/^(?<action>.*)( IGNORE)? INTO (?<table>.*) \(/';

        preg_match($update, $sql, $matchesUpdate);
        preg_match($insert, $sql, $matchesInsert);

        return [
            'table' => $matchesUpdate['table'] ?? $matchesInsert['table'] ?? null,
            'action' => $matchesUpdate['action'] ?? $matchesInsert['action'] ?? null,
        ];
    }

}