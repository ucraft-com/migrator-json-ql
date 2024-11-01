<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit;

use PHPUnit\Framework\TestCase;
use QLParser\ConditionEvaluator\ConditionEvaluator;
use QLParser\QueryEvaluator\QueryEvaluator;

class QueryEvaluatorTest extends TestCase
{
    public function testEvaluate_WhenGivenCorrectCondition_ReturnsTrue(): void
    {
        $query = [
            "findBy" => [
                "AND" => [
                    [
                        "key"      => "type",
                        "value"    => "button",
                        "operator" => "EQ"
                    ],
                    [
                        "OR" => [
                            [
                                "key"      => "params.variantType",
                                "value"    => "FORM_GIFT_CARD_DETAILS",
                                "operator" => "EQ"
                            ],
                            [
                                "key"      => "settings.other",
                                "value"    => "something",
                                "operator" => "EQ"
                            ]
                        ]
                    ],
                ]
            ]
        ];

        $queryEvaluator = $this->getInstance();
        $result = $queryEvaluator->evaluate($this->getData(), $query['findBy']);

        $this->assertTrue($result);
    }

    public function testEvaluate_WhenGivenNotCorrectCondition_ReturnsFalse(): void
    {
        $query = [
            "findBy" => [
                "AND" => [
                    [
                        "key"      => "type",
                        "value"    => "input",
                        "operator" => "EQ"
                    ],
                    [
                        "OR" => [
                            [
                                "key"      => "params.variantType",
                                "value"    => "FORM_GIFT_CARD_DETAILS",
                                "operator" => "EQ"
                            ],
                            [
                                "key"      => "settings.other",
                                "value"    => "something",
                                "operator" => "EQ"
                            ]
                        ]
                    ],
                ]
            ]
        ];

        $queryEvaluator = $this->getInstance();
        $result = $queryEvaluator->evaluate($this->getData(), $query['findBy']);

        $this->assertFalse($result);
    }

    public function testEvaluate_WhenGivenWrongOperator_ReturnsFalse(): void
    {
        $query = [
            "findBy" => [
                "and" => [
                    [
                        "key"      => "type",
                        "value"    => "input",
                        "operator" => "EQ"
                    ],
                ]
            ]
        ];

        $queryEvaluator = $this->getInstance();
        $result = $queryEvaluator->evaluate($this->getData(), $query['findBy']);

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

    protected function getInstance(): QueryEvaluator
    {
        return new QueryEvaluator(new ConditionEvaluator());
    }
}
