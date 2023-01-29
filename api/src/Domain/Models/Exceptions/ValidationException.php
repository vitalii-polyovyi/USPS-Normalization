<?php

namespace App\AddressNormalizer\Domain\Models\Exceptions;

use App\AddressNormalizer\Exceptions\AbstractHasErrorsException;

class ValidationException extends AbstractHasErrorsException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message ? : 'Validation failed', $code, $previous);
    }
}
