<?php

declare(strict_types=1);

namespace QLParser\QueryEvaluator;

use QLParser\ConditionEvaluator\ConditionEvaluatorInterface;

class QueryEvaluator implements QueryEvaluatorInterface
{
    public function __construct(protected ConditionEvaluatorInterface $conditionEvaluator)
    {
    }

    /**
     * @param array $data
     * @param array $query
     *
     * @return bool
     */
    public function evaluate(array $data, array $query): bool
    {
        // Check for AND conditions
        if (isset($query['AND'])) {
            $result = true; // Default result for AND conditions is true
            foreach ($query['AND'] as $condition) {
                // Recursively evaluate sub-conditions or individual condition
                if (isset($condition['AND']) || isset($condition['OR'])) {
                    // If the condition is a subquery, evaluate recursively
                    $result = $result && $this->evaluate($data, $condition);
                } else {
                    // Handle individual conditions
                    $result = $result && $this->conditionEvaluator->evaluate($data, $condition);
                }
            }

            return $result;
        }

        // Check for OR conditions
        if (isset($query['OR'])) {
            $result = false; // Default result for OR conditions is false
            foreach ($query['OR'] as $condition) {
                // Recursively evaluate sub-conditions or individual condition
                if (isset($condition['AND']) || isset($condition['OR'])) {
                    // If the condition is a subquery, evaluate recursively
                    $result = $result || $this->evaluate($data, $condition);
                } else {
                    // Handle individual conditions
                    $result = $result || $this->conditionEvaluator->evaluate($data, $condition);
                }
            }

            return $result;
        }

        // Return false for unsupported query structures
        return false;
    }
}
