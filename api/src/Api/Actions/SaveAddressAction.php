<?php

namespace App\AddressNormalizer\Api\Actions;

use App\AddressNormalizer\Api\JsonResponse;
use App\AddressNormalizer\Dto\AddressDto;
use App\AddressNormalizer\Services\NormalizeAddressServiceInterface;
use App\AddressNormalizer\Services\SaveAddressServiceInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SaveAddressAction implements ActionInterface
{
    public function __construct(private readonly SaveAddressServiceInterface $addressService)
    {
    }

    public function execute(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = json_decode($request->getBody(), true);

        logger()->info('Received save request', $body);

        $requestAddress = new AddressDto(
            $body['line1'] ?? '',
            $body['line2'] ?? '',
            $body['city'] ?? '',
            $body['state'] ?? '',
            $body['zipCode'] ?? ''
        );

        $this->addressService->saveAddress($requestAddress);

        return JsonResponse::make($response, ['Address saved successfully!']);
    }
}
