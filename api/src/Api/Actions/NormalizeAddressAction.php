<?php

namespace App\AddressNormalizer\Api\Actions;

use App\AddressNormalizer\Api\JsonResponse;
use App\AddressNormalizer\Dto\AddressDto;
use App\AddressNormalizer\Services\NormalizeAddressServiceInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class NormalizeAddressAction implements ActionInterface
{
    public function __construct(private readonly NormalizeAddressServiceInterface $addressService)
    {
    }

    public function execute(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = json_decode($request->getBody(), true);

        logger()->info('Received normalize request', $body);

        $requestAddress = new AddressDto(
            $body['line1'] ?? '',
            $body['line2'] ?? '',
            $body['city'] ?? '',
            $body['state'] ?? '',
            $body['zipCode'] ?? ''
        );

        $normalizedAddress = $this->addressService->getNormalizedAddress($requestAddress);

        return JsonResponse::make($response, $normalizedAddress);
    }
}
