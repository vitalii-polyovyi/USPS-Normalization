<?php

namespace App\AddressNormalizer\Domain\Models;

use App\AddressNormalizer\Domain\Models\Exceptions\ValidationException;
use App\AddressNormalizer\Domain\Models\Validation\FieldRulesValidator;
use App\AddressNormalizer\Validation\EmptyRule;
use App\AddressNormalizer\Validation\MaxLenRule;
use App\AddressNormalizer\Validation\MinLenRule;
use App\AddressNormalizer\Validation\StringRule;

class ApiKey extends AbstractModel
{
    private string $id;

    private string $key;

    private array $domains;

    public function validate(): void
    {
        $validator = FieldRulesValidator::make()
            ->add('key', [
                new EmptyRule($this->key),
                new StringRule($this->key),
                new MaxLenRule($this->key, ['len' => 1024])
            ])
            ->add('domains', [
                new EmptyRule($this->domains),
            ]);

        if (!$validator->validate()) {
            throw (new ValidationException())->addErrors($validator->errors());
        }
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function setDomains(array $domains): static
    {
        $this->domains = $domains;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getDomains(): array
    {
        return $this->domains;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'key' => $this->getKey(),
            'domains' => $this->getDomains(),
        ];
    }
}
