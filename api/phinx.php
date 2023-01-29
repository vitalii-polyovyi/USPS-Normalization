<?php

/**
 * @file
 * Migration configuration
 */

use DevCoder\DotEnv;

if (file_exists(__DIR__ . '/.env')) {
    (new DotEnv(__DIR__ . '/.env'))->load();
}

$environment = getenv('APP_ENV');

$config = [
    'adapter' => getenv('DATABASE_DRIVER'),
    'host' => getenv('DATABASE_HOST'),
    'name' => getenv('DATABASE_NAME'),
    'user' => getenv('DATABASE_USER'),
    'pass' => getenv('DATABASE_PASSWORD'),
    'port' => getenv('DATABASE_PORT'),
    'charset' => getenv('DATABASE_ENCODING'),
];

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => $environment,
        $environment => $config
    ],
    'version_order' => 'creation'
];
