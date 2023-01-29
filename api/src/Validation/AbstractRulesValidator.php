<?php

namespace App\AddressNormalizer\Validation;

abstract class AbstractRulesValidator
{
    final private function __construct()
    {
    }

    public static function make(): static
    {
        return new static();
    }

    abstract public function validate(): bool;

    /**
     * @return array<int, string>
     */
    abstract public function errors(): array;
}
