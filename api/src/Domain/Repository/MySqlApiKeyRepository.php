<?php

namespace App\AddressNormalizer\Domain\Repository;

use App\AddressNormalizer\Domain\Models\ApiKey;

class MySqlApiKeyRepository extends AbstractMysqlRepository implements ApiKeyRepositoryInterface
{
    public function getByKey(string $key): ?ApiKey
    {
        $sql = 'SELECT * FROM `api_keys` WHERE `key` = ? LIMIT 1';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute([$key]);
        $row = $stmt->fetch();

        return $row
            ? ApiKey::new()
                ->setId($row['id'])
                ->setKey($row['key'])
                ->setDomains(json_decode($row['domains'], true))
            : null;
    }
}
