<?php

declare(strict_types=1);

namespace QLParser\ConditionEvaluator;

use QLParser\Enums\ConditionalOperatorEnum;
use QLParser\Utils\Arr;

class ConditionEvaluator implements ConditionEvaluatorInterface
{
    /**
     * @param array $data
     * @param array $condition
     *
     * @return bool
     */
    public function evaluate(array $data, array $condition): bool
    {
        // Extract condition details
        $key = $condition['key'];
        $value = $condition['value'] ?? null;
        $operator = $condition['operator'];

        // Get the actual value from the data using the Arr helper
        $actualValue = Arr::get($data, $key);

        // Evaluate the condition based on the operator
        $operatorEnum = ConditionalOperatorEnum::tryFrom($operator);

        if ($operatorEnum) {
            return $operatorEnum->compare($actualValue, $value);
        }

        return false; // Unsupported operator
    }
}
