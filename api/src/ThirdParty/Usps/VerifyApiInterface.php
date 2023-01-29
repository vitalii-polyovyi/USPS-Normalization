<?php

namespace App\AddressNormalizer\ThirdParty\Usps;

use App\AddressNormalizer\Dto\AddressDto;
use Psr\Http\Message\ResponseInterface;

interface VerifyApiInterface
{
    public function request(AddressDto $address): AddressDto;
}
