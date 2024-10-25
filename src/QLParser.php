<?php

declare(strict_types=1);

namespace QLParser;

use QLParser\Operation\OperationContext;
use QLParser\QueryEvaluator\QueryEvaluatorInterface;

class QLParser
{
    public function __construct(
        protected QueryEvaluatorInterface $queryEvaluator,
        protected OperationContext $operationContext
    )
    {
    }

    // Main method to evaluate query and return filtered data
    public function parseData(array $data, array $query): ?array
    {
        // Check if the query evaluates to true
        if ($this->queryEvaluator->evaluate($data, $query)) {
            // If all conditions are true, return the data
            return $data;
        }

        // Return null or empty result if conditions are not met
        return null;
    }

    /**
     * @param array $data
     * @param array $operations
     *
     * @return void
     * @throws \QLParser\Operation\Exceptions\InvalidOperationOperatorException
     */
    public function applyOperations(array &$data, array $operations): void
    {
        foreach ($operations as $operation) {
            $this->operationContext->applyOperation($data, $operation);
        }
    }
}
