<?php

/**
 * @file
 * Entry point of the application
 */

if (PHP_SAPI === 'cli') {
    die('CLI execution is forbidden' . PHP_EOL);
}

use App\AddressNormalizer\Api\App;

/**
 * @var DI\Container $container
 */
$container = require_once __DIR__ . '/../bootstrap.php';

App::make($container)->run();
