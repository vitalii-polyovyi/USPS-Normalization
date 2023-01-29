<?php

namespace App\AddressNormalizer\Validation;

final class RulesValidator extends AbstractRulesValidator
{
    /**
     * @var array<int, array{rules: array<AbstractRule>, operator: Operators>
     */
    private array $rules = [];

    /**
     * @var array<int, string>
     */
    private array $errors = [];

    /**
     * @param AbstractRule|array<AbstractRule> $rule
     * @param Operators $operator
     * @return $this
     */
    public function add(mixed $rule, Operators $operator = Operators::Or): self
    {
        $this->rules[] = [
            'rules' => is_array($rule) ? $rule : [$rule],
            'operator' => $operator
        ];

        return $this;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $entry) {
            $rules = $entry['rules'];
            $errors = [];
            foreach ($rules as $rule) {
                if (!$rule->isValid()) {
                    $errors[] = $rule->getMessage();
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

    /**
     * @return array<int, string>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
