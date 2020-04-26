<?php

$container = new DI\ContainerBuilder();

$container->useAutowiring(true);
$container->useAnnotations(false);

$container->addDefinitions(include('./diDefinitions.php'));

if ($_SERVER['PHP_ENV'] == 'prod' || strpos($_SERVER['PHP_ENV'], 'staging') !== false) {
    $container->enableCompilation(__DIR__ . '/cache');
}

$container = $container->build();