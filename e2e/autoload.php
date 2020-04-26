<?php

$rootDir = dirname(__DIR__);

require_once "{$rootDir}/vendor/autoload.php";

spl_autoload_register(function ($_className) use ($rootDir) {
    $className = "{$rootDir}/src/" . str_replace('\\', '/', $_className) . '.php';

    if (file_exists($className)) {
        require_once $className;
    }

    $className = "{$rootDir}/e2e/" . str_replace('\\', '/', $_className) . '.php';

    if (file_exists($className)) {
        require_once $className;
    }
});

