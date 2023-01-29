<?php

namespace App\AddressNormalizer\Api\Actions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ActionInterface
{
    /**
     * @param RequestInterface|ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function execute(
        RequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface;
}
