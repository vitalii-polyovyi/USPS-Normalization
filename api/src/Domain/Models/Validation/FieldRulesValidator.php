<?php

namespace App\AddressNormalizer\Domain\Models\Validation;

use App\AddressNormalizer\Validation\AbstractRule;
use App\AddressNormalizer\Validation\AbstractRulesValidator;
use App\AddressNormalizer\Validation\Operators;

class FieldRulesValidator extends AbstractRulesValidator
{
    /**
     * @var array<int, array{field: string, rules: array<AbstractRule>, operator: Operators>
     */
    protected array $rules = [];

    /**
     * @var array<int, string>
     */
    protected array $errors = [];

    /**
     * @param string $field
     * @param AbstractRule|array<AbstractRule> $rule
     * @param Operators $operator
     * @return static
     */
    public function add(string $field, mixed $rule, Operators $operator = Operators::Or): self
    {
        $this->rules[] = [
            'field' => $field,
            'rules' => is_array($rule) ? $rule : [$rule],
            'operator' => $operator
        ];

        return $this;
    }

    private function prettifyField(string $field): string
    {
        return str_replace(' ', '_', ucfirst($field));
    }

    public function validate(): bool
    {
        foreach ($this->rules as $entry) {
            $rules = $entry['rules'];
            $field = $entry['field'];
            $errors = [];
            foreach ($rules as $rule) {
                if (!$rule->isValid()) {
                    $errors[] = $this->prettifyField($field) . ': ' . $rule->getMessage();
                }
            }

            $operator = $entry['operator'];

            if ($operator === Operators::Or && count($errors) > 0 ||
                $operator === Operators::And && count($errors) === count($rules)) {
                array_push($this->errors, ...$errors);
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
