<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit;

use PHPUnit\Framework\TestCase;
use QLParser\ConditionEvaluator\ConditionEvaluator;
use QLParser\QueryEvaluator\QueryEvaluator;

class QueryEvaluatorTest extends TestCase
{
    public function testEvaluate_WhenGivenANDCondition_ReturnsTrue(): void
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
                                "key"      => "type",
                                "value"    => "button",
                                "operator" => "EQ"
                            ],
                            [
                                "key"      => "type",
                                "value"    => "button",
                                "operator" => "EQ"
                            ]
                        ]
                    ],
                ]
            ]
        ];
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
