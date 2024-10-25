<?php

declare(strict_types=1);

namespace QLParser\ConditionEvaluator;

interface ConditionEvaluatorInterface
{
    /**
     * @param array $data
     * @param array $condition
     *
     * @return bool
     */
    public function evaluate(array $data, array $condition): bool;
}
