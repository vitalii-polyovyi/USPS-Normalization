<?php

namespace App\AddressNormalizer\Domain\Models;

use App\AddressNormalizer\Domain\Models\Exceptions\ValidationException;
use App\AddressNormalizer\Domain\Models\Validation\FieldRulesValidator;
use App\AddressNormalizer\Validation\EmptyRule;
use App\AddressNormalizer\Validation\MaxLenRule;
use App\AddressNormalizer\Validation\MinLenRule;
use App\AddressNormalizer\Validation\StringRule;

class Address extends AbstractModel
{
    private string $line1;
    private string $line2;
    private string $city;
    private string $state;
    private string $zipCode;

    public function validate(): void
    {
        $validator = FieldRulesValidator::make()
            ->add('line1', [
                new EmptyRule($this->line1),
                new StringRule($this->line1),
                new MaxLenRule($this->line1, ['len' => 46])
            ])
            ->add('line2', [
                new EmptyRule($this->line2),
                new StringRule($this->line2),
                new MaxLenRule($this->line2, ['len' => 46])
            ])
            ->add('city', [
                new EmptyRule($this->city),
                new StringRule($this->city),
                new MaxLenRule($this->city, ['len' => 50])
            ])
            ->add('state', [
                new StringRule($this->state),
                new MinLenRule($this->state, ['len' => 2]),
                new MaxLenRule($this->state, ['len' => 50])
            ])
            ->add('zipCode', [
                new MinLenRule($this->zipCode, ['len' => 5]),
                new MaxLenRule($this->zipCode, ['len' => 10])
            ]);

        if (!$validator->validate()) {
            throw (new ValidationException())->addErrors($validator->errors());
        }
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setLine1(string $line1): static
    {
        $this->line1 = $line1;

        return $this;
    }

    public function setLine2(string $line2): static
    {
        $this->line2 = $line2;

        return $this;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getLine1(): string
    {
        return $this->line1;
    }

    public function getLine2(): string
    {
        return $this->line2;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'line1' => $this->getLine1(),
            'line2' => $this->getLine2(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'zipCode' => $this->getZipCode(),
        ];
    }
}
