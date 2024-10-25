<?php

declare(strict_types=1);

namespace QLParser\Enums;

/**
 * QueryOperatorEnum defines query operator types.
 * AND - and operator.
 * OR - or operator.
 */
enum QueryOperatorEnum : string
{
    case AND = 'AND';
    case OR = 'OR';
}
