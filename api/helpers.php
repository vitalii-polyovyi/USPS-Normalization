<?php

function logger(): \Psr\Log\LoggerInterface
{
    $streamHandler = new \Monolog\Handler\StreamHandler('php://stdout');
    $logger = new \Monolog\Logger('App\AddressNormalizer');
    $logger->pushHandler($streamHandler);

    return $logger;
}
