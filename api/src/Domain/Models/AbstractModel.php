<?php

namespace App\AddressNormalizer\Domain\Models;

use App\AddressNormalizer\Domain\Models\Exceptions\ValidationException;

abstract class AbstractModel implements \JsonSerializable
{
    private ?string $id = null;

    private function __construct()
    {
    }

    final public static function new(): static
    {
        return new static();
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return void
     * @throws ValidationException
     */
    abstract public function validate(): void;
}
