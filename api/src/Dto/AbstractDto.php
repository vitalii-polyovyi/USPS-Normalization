<?php

namespace App\AddressNormalizer\Dto;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractDto implements \JsonSerializable
{
    /**
     * Get public values.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
