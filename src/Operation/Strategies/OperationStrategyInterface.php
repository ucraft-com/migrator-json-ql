<?php

declare(strict_types=1);

namespace QLParser\Operation\Strategies;

interface OperationStrategyInterface
{
    /**
     * @param array      $data
     * @param string     $path
     * @param mixed|null $value
     *
     * @return void
     */
    public function apply(array &$data, string $path, mixed $value = null): void;
}
