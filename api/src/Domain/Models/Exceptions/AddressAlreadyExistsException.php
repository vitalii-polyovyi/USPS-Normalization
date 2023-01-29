<?php

namespace App\AddressNormalizer\Domain\Models\Exceptions;

class AddressAlreadyExistsException extends ValidationException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->addErrors(['Address already exists']);
    }
}
