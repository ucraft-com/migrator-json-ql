<?php

declare(strict_types=1);

namespace QLParser\Tests\Unit\Operation;

use PHPUnit\Framework\TestCase;
use QLParser\Operation\Exceptions\InvalidOperationOperatorException;
use QLParser\Operation\OperationContext;
use QLParser\Operation\OperationStrategyFactory;

class OperationContextTest extends TestCase
{
    public function testApplyReplaceOperation_WhenGivenValidData_ReturnsMutatedData(): void
    {
        $operation = [
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
        ];

        $data = $this->applyOperation($operation);

        $this->assertEquals($data['params']['settings']['name'], 'newContentType');
        $this->assertEquals($data['params']['variantsStyles'][0]['breakpointId'], '3');
    }

    public function testApplyRemoveOperation_WhenGivenValidData_ReturnsMutatedData(): void
    {
        $operation = [
            'op'   => 'REMOVE',
            'data' => [
                [
                    'where' => 'params.settings.name',
                    'what'  => null
                ],
            ]
        ];

        $data = $this->applyOperation($operation);

        $this->assertEmpty($data['params']['settings']);
    }

    public function testApplyAddOperation_WhenGivenValidData_ReturnsMutatedData(): void
    {
        $operation = [
            'op'   => 'ADD',
            'data' => [
                [
                    'where' => 'params.settings',
                    'what'  => ['aa' => 'newContent', 'bb' => 'bb']
                ],
            ],
        ];

        $data = $this->applyOperation($operation);

        $this->assertEquals($data['params']['settings']['name'], 'contentType');
        $this->assertEquals($data['params']['settings']['aa'], 'newContent');
        $this->assertEquals($data['params']['settings']['bb'], 'bb');
    }

    public function testApplyMultipleOperations_WhenGivenValidData_ReturnsMutatedData(): void
    {
        $operations = [
            [
                'op'   => 'ADD',
                'data' => [
                    [
                        'where' => 'params.settings',
                        'what'  => ['aa' => 'newContent', 'bb' => 'bb']
                    ],
                ]
            ],
            [
                'op'   => 'REMOVE',
                'data' => [
                    [
                        'where' => 'params.settings.aa',
                        'what'  => null
                    ],
                ]
            ]
        ];

        $data = [
            'type'   => 'button',
            'params' => [
                'variantType'    => 'FORM_GIFT_CARD_DETAILS',
                'settings'       => [
                    'name' => 'contentType'
                ],
                'variantsStyles' => []
            ]
        ];

        foreach ($operations as $operation) {
            $data = $this->applyOperation($operation, $data);
        }

        $this->assertArrayNotHasKey('aa', $data['params']['settings']);
        $this->assertEquals($data['params']['settings']['bb'], 'bb');
    }

    public function testApplyNotValidOperation_ReturnsException(): void
    {
        $this->expectException(InvalidOperationOperatorException::class);
        $operation = [
            'op'   => 'NotValid',
            'data' => [
                [
                    'where' => 'params.settings.name',
                    'what'  => null
                ],
            ]
        ];

        $this->applyOperation($operation);
    }

    /**
     * @param array      $operations
     * @param array|null $data
     *
     * @return array
     * @throws \QLParser\Operation\Exceptions\InvalidOperationOperatorException
     */
    protected function applyOperation(array $operations, array $data = null): array
    {
        $data ??= [
            'type'   => 'button',
            'params' => [
                'variantType'    => 'FORM_GIFT_CARD_DETAILS',
                'settings'       => [
                    'name' => 'contentType'
                ],
                'variantsStyles' => []
            ]
        ];

        $context = new OperationContext(new OperationStrategyFactory());
        $context->applyOperation($data, $operations);

        return $data;
    }
}
