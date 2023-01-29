<?php

namespace App\AddressNormalizer\Domain\Repository;

use App\AddressNormalizer\Domain\Models\Address;
use App\AddressNormalizer\Domain\Models\ApiKey;
use App\AddressNormalizer\Dto\AddressDto;

interface ApiKeyRepositoryInterface
{
    /**
     * @param string $key
     * @return ApiKey|null
     */
    public function getByKey(string $key): ?ApiKey;
}
