<?php

namespace App\AddressNormalizer\Domain\Repository;

use App\AddressNormalizer\Domain\Models\Address;
use App\AddressNormalizer\Dto\AddressDto;

interface AddressRepositoryInterface
{
    /**
     * @param AddressDto $addressDto
     * @return Address
     */
    public function save(AddressDto $addressDto): Address;

    /**
     * @param AddressDto $addressDto
     * @return Address|null
     */
    public function getByAllFields(AddressDto $addressDto): ?Address;
}
