<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit\Operation\Strategies;

use PHPUnit\Framework\TestCase;
use QLParser\Operation\Strategies\ReplaceOperation;

class ReplaceOperationStrategyTest extends TestCase
{
    public function testReplaceOperation_WhenGivenSimpleKeyToReplaceFromArray_ReturnsReplacedData(): void
    {
        $data = $this->applyOperation('params.settings.name', 'newName');

        $this->assertEquals('newName', $data['params']['settings']['name']);
    }

    public function testReplaceOperation_WhenGivenKeyThatIsArray_ReturnsRemovedData(): void
    {
        $data = $this->applyOperation('params.settings', ['newSettings' => 'newValue']);

        $this->assertArrayHasKey('newSettings', $data['params']['settings']);
        $this->assertEquals('newValue', $data['params']['settings']['newSettings']);
    }

    protected function applyOperation(string $path, mixed $value): array
    {
        $data = [
            'type'   => 'button',
            'params' => [
                'variantType' => 'FORM_GIFT_CARD_DETAILS',
                'settings' => [
                    'name' => 'contentType'
                ],
                'variantsStyles' => []
            ]
        ];

        $addStrategy = new ReplaceOperation();
        $addStrategy->apply($data, $path, $value);

        return $data;
    }
}
