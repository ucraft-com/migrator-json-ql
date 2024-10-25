<?php

declare(strict_types=1);

namespace QLParser\Operation;

use QLParser\Enums\OperationOperatorEnum;
use QLParser\Operation\Exceptions\InvalidOperationOperatorException;

use function sprintf;

class OperationContext
{
    public function __construct(protected OperationStrategyFactory $factory)
    {
    }

    /**
     * @param array $data
     * @param array $operation
     *
     * @return void
     * @throws \QLParser\Operation\Exceptions\InvalidOperationOperatorException
     */
    public function applyOperation(array &$data, array $operation): void
    {
        $opType = OperationOperatorEnum::tryFrom($operation['op']);

        if (null === $opType) {
            throw new InvalidOperationOperatorException(sprintf('Operation operator [%s] not supported.', $operation['op']));
        }

        $strategy = $this->factory->makeStrategy($opType);

        foreach ($operation['data'] as $opData) {
            $path = $opData['where'];
            $value = $opData['what'] ?? null;
            $strategy->apply($data, $path, $value);
        }
    }
}
