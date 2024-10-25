<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit;

use PHPUnit\Framework\TestCase;
use QLParser\ConditionEvaluator\ConditionEvaluator;
use QLParser\Operation\OperationContext;
use QLParser\Operation\OperationStrategyFactory;
use QLParser\QLParser;
use QLParser\QueryEvaluator\QueryEvaluator;

class QLParserTest extends TestCase
{
    public function testParseData_WhenGivenEQOperatorAndValidValue_ReturnsTrue(): void
    {
        $data = $this->getData();

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

        $operations = [
            [
                'op'   => 'REPLACE',
                'data' => [
                    [
                        'where' => 'params.settings.name',
                        'what'  => 'newContentType'
                    ],
                    [
                        'where' => 'params.variantsStyles',
                        'what'  => [
                            [
                                'breakpointId' => '3',
                                'cssState'     => 'normal',
                                'styles'       => []
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $qlParser = $this->createInstance();
        $result = $qlParser->parseData($data, $query['findBy']);

        $qlParser->applyOperations($result, $operations);

        $this->assertEquals($result['params']['settings']['name'], 'newContentType');
        $this->assertEquals($result['params']['variantsStyles'][0]['breakpointId'], '3');
    }

    public function testParseData_WhenGivenNotValidFindBy_ReturnsNull(): void
    {
        $data = $this->getData();

        $query = [
            "findBy" => [
                "ANDDADA" => []
            ]
        ];

        $qlParser = $this->createInstance();
        $result = $qlParser->parseData($data, $query['findBy']);

        $this->assertNull($result);
    }

    public function testParseData_WhenGivenValidNEQ_ReturnsData(): void
    {
        $data = $this->getData();

        $query = [
            "findBy" => [
                "AND" =>[
                    [
                        "key"      => "params.settings.name",
                        "value"    => "button",
                        "operator" => "NEQ"
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
                                "value"    => "input",
                                "operator" => "EQ"
                            ]
                        ]
                    ],
                ]
            ]
        ];

        $qlParser = $this->createInstance();
        $result = $qlParser->parseData($data, $query['findBy']);

        $this->assertNotNull($result);
    }

    public function testParseData_WhenGivenValidANDConditionAndNotValidORCondition_ReturnsNull(): void
    {
        $data = $this->getData();

        $query = [
            "findBy" => [
                "AND" =>[
                    [
                        "key"      => "type",
                        "value"    => "button",
                        "operator" => "EQ"
                    ],
                    [
                        "OR" => [
                            [
                                "key"      => "params.variantType",
                                "value"    => "abc",
                                "operator" => "EQ"
                            ],
                            [
                                "key"      => "params.settings.name",
                                "value"    => "input",
                                "operator" => "EQ"
                            ]
                        ]
                    ],
                ]
            ]
        ];

        $qlParser = $this->createInstance();
        $result = $qlParser->parseData($data, $query['findBy']);

        $this->assertNull($result);
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
     * @return \QLParser\QLParser
     */
    protected function createInstance(): QLParser
    {
        return new QLParser(new QueryEvaluator(new ConditionEvaluator()), new OperationContext(new OperationStrategyFactory()));
    }
}
