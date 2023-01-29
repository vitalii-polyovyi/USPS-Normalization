<?php

/**
 * @file
 * App bootstrapping
 */

use DI\ContainerBuilder;
use DevCoder\DotEnv;

require __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    (new DotEnv(__DIR__ . '/.env'))->load();
}

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/di-config.php');
$containerBuilder->useAttributes(false);
$container = $containerBuilder->build();

require __DIR__ . '/helpers.php';

return $container;
