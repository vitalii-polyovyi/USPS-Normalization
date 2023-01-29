<?php

namespace App\AddressNormalizer\Services;

use App\AddressNormalizer\Dto\AddressDto;
use App\AddressNormalizer\Domain\Repository\AddressRepositoryInterface;

interface SaveAddressServiceInterface
{
    public function __construct(AddressRepositoryInterface $addressRepository);

    /**
     * Persist an address into a storage
     *
     * @param AddressDto $address
     * @return void
     */
    public function saveAddress(AddressDto $address): void;
}
