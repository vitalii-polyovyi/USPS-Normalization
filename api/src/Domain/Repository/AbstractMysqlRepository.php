<?php

namespace App\AddressNormalizer\Domain\Repository;

use PDO;

abstract class AbstractMysqlRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
