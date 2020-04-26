<?php

return [
    'databaseConfig' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'Lion1234',
        'database' => 'php_e2e_example',
        'port' => 3305,
    ],
    'curlConfig' => [
        'freshConnect' => true,
        'returnTransfer' => true,
        'verifySSLHost' => true,
        'verifySSLPeer' => true,
        'connectTimeout' => true,
        'timeout' => 5,
        'post' => true,
    ],
];