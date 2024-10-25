<?php

declare(strict_types=1);

namespace QLParser\Operation;

use QLParser\Enums\OperationOperatorEnum;
use QLParser\Operation\Strategies\AddOperation;
use QLParser\Operation\Strategies\OperationStrategyInterface;
use QLParser\Operation\Strategies\RemoveOperation;
use QLParser\Operation\Strategies\ReplaceOperation;

class OperationStrategyFactory
{
    /**
     * @param \QLParser\Enums\OperationOperatorEnum $opType
     *
     * @return \QLParser\Operation\Strategies\OperationStrategyInterface
     */
    public function makeStrategy(OperationOperatorEnum $opType): OperationStrategyInterface
    {
        return match ($opType) {
            OperationOperatorEnum::ADD => new AddOperation(),
            OperationOperatorEnum::REMOVE => new RemoveOperation(),
            OperationOperatorEnum::REPLACE => new ReplaceOperation(),
        };
    }
}
