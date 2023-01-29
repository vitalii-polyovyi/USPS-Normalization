<?php

namespace App\AddressNormalizer\Domain\Repository;

use App\AddressNormalizer\Domain\Models\Address;
use App\AddressNormalizer\Dto\AddressDto;

final class InMemoryAddressRepository implements AddressRepositoryInterface
{
    /**
     * @var array<Address>
     */
    private array $addresses = [];

    /**
     * @param AddressDto $addressDto
     * @return Address
     * @throws \App\AddressNormalizer\Domain\Models\Exceptions\ValidationException
     */
    public function save(AddressDto $addressDto): Address
    {
        $address = Address::new()
            ->setLine1($addressDto->line1)
            ->setLine2($addressDto->line2)
            ->setCity($addressDto->city)
            ->setState($addressDto->state)
            ->setZipCode($addressDto->zipCode);

        $address->validate();

        $this->addresses[] = $address;

        logger()->info('Current addresses:' . json_encode($this->addresses));

        return $address;
    }

    public function getByAllFields(AddressDto $addressDto): ?Address
    {
        foreach ($this->addresses as $address) {
            if ($address->getLine1() === $addressDto->line1 &&
                $address->getLine2() === $addressDto->line2 &&
                $address->getCity() === $addressDto->city &&
                $address->getState() === $addressDto->state &&
                $address->getZipCode() === $addressDto->zipCode) {
                return $address;
            }
        }

        return null;
    }
}
