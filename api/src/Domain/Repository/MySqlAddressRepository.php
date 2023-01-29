<?php

namespace App\AddressNormalizer\Domain\Repository;

use App\AddressNormalizer\Domain\Models\Address;
use App\AddressNormalizer\Dto\AddressDto;

class MySqlAddressRepository extends AbstractMysqlRepository implements AddressRepositoryInterface
{
    public function save(AddressDto $address): Address
    {
        $sql = "INSERT INTO addresses (line1, line2, city, state, zipCode) VALUES (?,?,?,?,?)";
        $stmt= $this->getPdo()->prepare($sql);
        $stmt->execute([$address->line1, $address->line2, $address->city, $address->state, $address->zipCode]);
        $id = $this->getPdo()->lastInsertId();

        return Address::new()
            ->setId($id)
            ->setLine1($address->line1)
            ->setLine2($address->line2)
            ->setCity($address->city)
            ->setState($address->state)
            ->setZipCode($address->zipCode);
    }

    public function getByAllFields(AddressDto $address): ?Address
    {
        $sql = 'SELECT * FROM `addresses` WHERE ' .
            '`line1` = ? AND `line2` = ? AND city = ? AND state = ? AND zipcode = ? LIMIT 1';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute([$address->line1, $address->line2, $address->city, $address->state, $address->zipCode]);
        $row = $stmt->fetch();

        return $row
            ? Address::new()
                ->setId($row['id'])
                ->setLine1($row['line1'])
                ->setLine2($row['line2'])
                ->setCity($row['city'])
                ->setState($row['state'])
                ->setZipCode($row['zipcode'])
            : null;
    }
}
