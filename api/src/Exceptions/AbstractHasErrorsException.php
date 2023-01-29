<?php

namespace App\AddressNormalizer\Exceptions;

abstract class AbstractHasErrorsException extends \Exception
{
    /**
     * @var array<string>
     */
    private array $errors = [];

    /**
     * @param array<int, string> $errors
     * @return $this
     */
    public function addErrors(array $errors): static
    {
        array_push($this->errors, ...$errors);

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
