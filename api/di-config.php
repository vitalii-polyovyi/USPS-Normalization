<?php

/**
 * @file
 * DI configuration
 */

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use App\AddressNormalizer\Services;
use App\AddressNormalizer\Domain\Repository;
use App\AddressNormalizer\ThirdParty\Usps;
use function DI\create;
use function DI\get;

$pdo = new PDO(
    'mysql:host=' . getenv('DATABASE_HOST') .
    ';port=' . getenv('DATABASE_PORT') . ';dbname=' . getenv('DATABASE_NAME'),
    getenv('DATABASE_USER'),
    getenv('DATABASE_PASSWORD'),
    [
        PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . getenv('DATABASE_ENCODING'),
    ]
);

return [
    RequestInterface::class => function () {
        return \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
    },
    ResponseInterface::class => create(\GuzzleHttp\Psr7\Response::class),
    Usps\VerifyApiInterface::class => create(Usps\VerifyApi::class)->constructor(getenv('USPS_USERNAME')),
    Repository\AddressRepositoryInterface::class => create(Repository\MySqlAddressRepository::class)
        ->constructor($pdo),
    Repository\ApiKeyRepositoryInterface::class => create(Repository\MySqlApiKeyRepository::class)
        ->constructor($pdo),
    Services\NormalizeAddressServiceInterface::class => create(Services\UspsNormalizeAddressService::class)
        ->constructor(get(Usps\VerifyApiInterface::class)),
    Services\SaveAddressServiceInterface::class => create(Services\SaveAddressService::class)
        ->constructor(get(Repository\AddressRepositoryInterface::class)),
];
