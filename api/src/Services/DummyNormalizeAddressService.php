<?php

namespace App\AddressNormalizer\Services;

use App\AddressNormalizer\Dto\AddressDto;

class DummyNormalizeAddressService implements NormalizeAddressServiceInterface
{
    public function getNormalizedAddress(AddressDto $address): AddressDto
    {
        return new AddressDto(...$address->toArray());
    }
}
