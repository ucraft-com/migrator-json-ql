<?php

declare(strict_types=1);

namespace QLParser\QueryEvaluator;

interface QueryEvaluatorInterface
{
    /**
     * @param array $data
     * @param array $query
     *
     * @return bool
     */
    public function evaluate(array $data, array $query): bool;
}
