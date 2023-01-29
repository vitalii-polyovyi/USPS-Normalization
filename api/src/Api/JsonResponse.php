<?php

namespace App\AddressNormalizer\Api;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public static function make(ResponseInterface $response, mixed $data, int $flags = 0): ResponseInterface
    {
        $body = $response->getBody();
        $body->write(json_encode($data, $flags | JSON_THROW_ON_ERROR));
        $response->withBody($body);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
