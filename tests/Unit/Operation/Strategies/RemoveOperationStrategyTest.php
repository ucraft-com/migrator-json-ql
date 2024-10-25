<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit\Operation\Strategies;

use PHPUnit\Framework\TestCase;
use QLParser\Operation\Strategies\RemoveOperation;

class RemoveOperationStrategyTest extends TestCase
{
    public function testRemoveOperation_WhenGivenSimpleKeyToRemoveFromArray_ReturnsRemovedData(): void
    {
        $data = $this->applyOperation('params.settings.name');

        $this->assertEmpty($data['params']['settings']);
    }

    public function testRemoveOperation_WhenGivenKeyThatIsArray_ReturnsRemovedData(): void
    {
        $data = $this->applyOperation('params.settings');

        $this->assertArrayNotHasKey('settings', $data['params']);
    }

    protected function applyOperation(string $path): array
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

        $addStrategy = new RemoveOperation();
        $addStrategy->apply($data, $path);

        return $data;
    }
}
