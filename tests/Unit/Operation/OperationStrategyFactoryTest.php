<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit\Operation;

use PHPUnit\Framework\TestCase;
use QLParser\Enums\OperationOperatorEnum;
use QLParser\Operation\OperationStrategyFactory;
use QLParser\Operation\Strategies\AddOperation;
use QLParser\Operation\Strategies\OperationStrategyInterface;
use QLParser\Operation\Strategies\RemoveOperation;
use QLParser\Operation\Strategies\ReplaceOperation;

class OperationStrategyFactoryTest extends TestCase
{
    public function testFactory_WhenGivenAddOperation_ReturnsAddOperationStrategy(): void
    {
        $result = $this->createInstanceStrategyFactory(OperationOperatorEnum::ADD);

        $this->assertInstanceOf(AddOperation::class, $result);
    }

    public function testFactory_WhenGivenRemoveOperation_ReturnsRemoveOperationStrategy(): void
    {
        $result = $this->createInstanceStrategyFactory(OperationOperatorEnum::REMOVE);

        $this->assertInstanceOf(RemoveOperation::class, $result);
    }

    public function testFactory_WhenGivenReplaceOperation_ReturnsReplaceOperationStrategy(): void
    {
        $result = $this->createInstanceStrategyFactory(OperationOperatorEnum::REPLACE);

        $this->assertInstanceOf(ReplaceOperation::class, $result);
    }

    /**
     * @param \QLParser\Enums\OperationOperatorEnum $type
     *
     * @return \QLParser\Operation\Strategies\OperationStrategyInterface
     */
    protected function createInstanceStrategyFactory(OperationOperatorEnum $type): OperationStrategyInterface
    {
        $strategyFactory = new OperationStrategyFactory();

        return $strategyFactory->makeStrategy($type);
    }
}
