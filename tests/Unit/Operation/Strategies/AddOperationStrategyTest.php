<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit\Operation\Strategies;

use PHPUnit\Framework\TestCase;
use QLParser\Operation\Strategies\AddOperation;

class AddOperationStrategyTest extends TestCase
{
    public function testAddOperation_WhenGivenSimpleStringToAddInArray_ReturnsAddedData(): void
    {
        $data = $this->applyOperation('params.settings', 'newContent');

        $this->assertEquals($data['params']['settings']['name'], 'contentType');
        $this->assertEquals($data['params']['settings'][0], 'newContent');
    }

    public function testAddOperation_WhenGivenKeyValueToAddInArray_ReturnsAddedData(): void
    {
        $data = $this->applyOperation('params.settings', ['aa' => 'newContent', 'bb' => 'bb']);

        $this->assertEquals($data['params']['settings']['name'], 'contentType');
        $this->assertEquals($data['params']['settings']['aa'], 'newContent');
        $this->assertEquals($data['params']['settings']['bb'], 'bb');
    }

    public function testAddOperation_WhenGivenKeyValueToAddInEmptyArray_ReturnsAddedData(): void
    {
        $data = [
            'type'   => 'button',
            'params' => [
                'variantType' => 'FORM_GIFT_CARD_DETAILS',
                'settings' => [],
                'variantsStyles' => []
            ]
        ];

        $data = $this->applyOperation('params.settings', ['aa' => 'newContent', 'bb' => 'bb'], $data);

        $this->assertEquals($data['params']['settings']['aa'], 'newContent');
        $this->assertEquals($data['params']['settings']['bb'], 'bb');
    }

    public function testAddOperation_WhenGivenExistingKey_ReturnsOverridedData(): void
    {
        $data = $this->applyOperation('params.settings.name', 'bb');

        $this->assertEquals($data['params']['settings']['name'], 'bb');
    }

    public function testAddOperation_WhenGivenNewKey_ReturnsAddedData(): void
    {
        $data = $this->applyOperation('params.newSettings', 'myValue');

        $this->assertEquals($data['params']['newSettings'], 'myValue');
    }

    protected function applyOperation(string $path, mixed $value, array $data = null): array
    {
        $data ??= [
            'type'   => 'button',
            'params' => [
                'variantType' => 'FORM_GIFT_CARD_DETAILS',
                'settings' => [
                    'name' => 'contentType'
                ],
                'variantsStyles' => []
            ]
        ];

        $addStrategy = new AddOperation();
        $addStrategy->apply($data, $path, $value);

        return $data;
    }
}
