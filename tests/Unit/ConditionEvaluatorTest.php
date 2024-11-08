<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit;

use PHPUnit\Framework\TestCase;
use QLParser\ConditionEvaluator\ConditionEvaluator;
use QLParser\Enums\ConditionalOperatorEnum;

class ConditionEvaluatorTest extends TestCase
{
    public function testEvaluate_WhenGivenEQOperatorAndValidValue_ReturnsTrue(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name',
            'value'    => 'contentType',
            'operator' => ConditionalOperatorEnum::EQ->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertTrue($result);
    }

    public function testEvaluate_WhenGivenEQOperatorAndNotValidValue_ReturnsFalse(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name',
            'value'    => 'otherValue',
            'operator' => ConditionalOperatorEnum::EQ->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertFalse($result);
    }

    public function testEvaluate_WhenGivenEQOperatorAndWrongKey_ReturnsFalse(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name.notExists',
            'value'    => 'contentType',
            'operator' => ConditionalOperatorEnum::EQ->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertFalse($result);
    }

    public function testEvaluate_WhenGivenNEQOperatorAndValidValue_ReturnsTrue(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name',
            'value'    => 'other',
            'operator' => ConditionalOperatorEnum::NEQ->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertTrue($result);
    }

    public function testEvaluate_WhenGivenNEQOperatorAndNotValidValue_ReturnsFalse(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name',
            'value'    => 'contentType',
            'operator' => ConditionalOperatorEnum::NEQ->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertFalse($result);
    }

    public function testEvaluate_WhenGivenNEQOperatorAndWrongKey_ReturnsFalse(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name.notExists',
            'value'    => null,
            'operator' => ConditionalOperatorEnum::NEQ->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertFalse($result);
    }

    public function testEvaluate_WhenGivenEMPTYOperatorAndValidValue_ReturnsTrue(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.variantsStyles',
            'operator' => ConditionalOperatorEnum::EMPTY->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertTrue($result);
    }

    public function testEvaluate_WhenGivenEMPTYOperatorAndNotValidValue_ReturnsFalse(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name',
            'operator' => ConditionalOperatorEnum::EMPTY->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertFalse($result);
    }

    public function testEvaluate_WhenGivenNEMPTYOperatorAndValidValue_ReturnsTrue(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.settings.name',
            'operator' => ConditionalOperatorEnum::NEMPTY->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertTrue($result);
    }

    public function testEvaluate_WhenGivenNEMPTYOperatorAndNotValidValue_ReturnsFalse(): void
    {
        $data = $this->getData();

        $conditions = [
            'key'      => 'params.variantsStyles',
            'operator' => ConditionalOperatorEnum::NEMPTY->value,
        ];

        $result = $this->createInstance()->evaluate($data, $conditions);

        $this->assertFalse($result);
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [
            'type'   => 'button',
            'params' => [
                'variantType'    => 'FORM_GIFT_CARD_DETAILS',
                'settings'       => [
                    'name' => 'contentType'
                ],
                'variantsStyles' => []
            ]
        ];
    }

    /**
     * @return \QLParser\ConditionEvaluator\ConditionEvaluator
     */
    protected function createInstance(): ConditionEvaluator
    {
        return new ConditionEvaluator();
    }
}
