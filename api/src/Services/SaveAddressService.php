<?php

namespace App\AddressNormalizer\Services;

use App\AddressNormalizer\Domain\Models\Exceptions\AddressAlreadyExistsException;
use App\AddressNormalizer\Dto\AddressDto;
use App\AddressNormalizer\Domain\Repository\AddressRepositoryInterface;

class SaveAddressService implements SaveAddressServiceInterface
{
    public function __construct(private readonly AddressRepositoryInterface $addressRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function saveAddress(AddressDto $address): void
    {
        $existing = $this->addressRepository->getByAllFields($address);
        if ($existing) {
            throw new AddressAlreadyExistsException();
        }

        $this->addressRepository->save($address);
    }
}
