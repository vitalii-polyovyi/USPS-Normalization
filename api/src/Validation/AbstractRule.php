<?php

namespace App\AddressNormalizer\Validation;

abstract class AbstractRule
{
    /**
     * @param int|string|float $value
     * @param array $params
     */
    final public function __construct(
        protected readonly mixed $value,
        protected readonly array $params = []
    ) {
    }

    public function getValue(): int|string|float
    {
        return $this->value;
    }

    abstract public function isValid(): bool;
    abstract public function getMessage(): string;
}
