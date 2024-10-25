<?php

declare(strict_types=1);

namespace QLParser\Enums;

/**
 * OperationOperatorEnum defines operation operator types.
 * ADD - add, add new data.
 * REMOVE - remove, remove data.
 * REPLACE - replace, replace data.
 */
enum OperationOperatorEnum : string
{
    case ADD = 'ADD';
    case REMOVE = 'REMOVE';
    case REPLACE = 'REPLACE';
}
