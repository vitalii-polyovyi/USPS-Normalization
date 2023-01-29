<?php

namespace App\AddressNormalizer\Dto;

class AddressDto extends AbstractDto
{
    /**
     * @param string $line1
     * @param string $line2
     * @param string $city
     * @param string $state
     * @param string $zipCode
     */
    public function __construct(
        public string $line1,
        public string $line2,
        public string $city,
        public string $state,
        public string $zipCode
    ) {
    }
}
