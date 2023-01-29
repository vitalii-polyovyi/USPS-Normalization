<?php

namespace App\AddressNormalizer\Services;

use App\AddressNormalizer\Dto\AddressDto;
use App\AddressNormalizer\ThirdParty\Usps\VerifyApiInterface;

class UspsNormalizeAddressService implements NormalizeAddressServiceInterface
{
    public function __construct(private readonly VerifyApiInterface $verifyApi)
    {
    }

    public function getNormalizedAddress(AddressDto $address): AddressDto
    {
        return $this->verifyApi->request($address);
    }
}
