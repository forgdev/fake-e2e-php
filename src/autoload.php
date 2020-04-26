<?php

spl_autoload_register(function ($_className) use ($rootDir) {
    $className = "{$rootDir}/src/" . str_replace('\\', '/', $_className) . '.php';

    if (file_exists($className)) {
        require_once $className;
    }
});

require_once "{$rootDir}/vendor/autoload.php";
