<?php

namespace App\AddressNormalizer\Validation;

class EmptyRule extends AbstractRule
{
    public function isValid(): bool
    {
        return !empty($this->value);
    }

    public function getMessage(): string
    {
        return 'given value is empty';
    }
}
