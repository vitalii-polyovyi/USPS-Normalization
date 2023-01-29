<?php

namespace App\AddressNormalizer\Validation;

class MinLenRule extends AbstractRule
{
    private function getLenParam(): int
    {
        return (int)$this->params['len'] ?? 0;
    }

    public function isValid(): bool
    {
        return mb_strlen($this->value) >= $this->getLenParam();
    }

    public function getMessage(): string
    {
        return 'minimum length is ' . $this->getLenParam();
    }
}
