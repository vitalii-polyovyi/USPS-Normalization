<?php

namespace App\AddressNormalizer\Validation;

class StringRule extends AbstractRule
{
    public function isValid(): bool
    {
        return is_string($this->value);
    }

    public function getMessage(): string
    {
        return 'given value is not a string';
    }
}
