<?php

namespace App\AddressNormalizer\Services;

use App\AddressNormalizer\Dto\AddressDto;

interface NormalizeAddressServiceInterface
{
    /**
     * Gets an address and returns a normalized version of it
     *
     * @param AddressDto $address
     * @return AddressDto
     */
    public function getNormalizedAddress(AddressDto $address): AddressDto;
}
