<?php

declare(strict_types=1);

namespace QLParser\Enums;

/**
 * ConditionalOperatorEnum defines conditional operator types.
 * EQ - equals, the conditions on this operator must be equal.
 * NEQ - not equal, the conditions on this operator must not be equal.
 */
enum ConditionalOperatorEnum : string
{
    case EQ = 'EQ';
    case NEQ = 'NEQ';

    /**
     * @param mixed $actualValue
     * @param mixed $value
     *
     * @return bool
     */
    public function compare(mixed $actualValue, mixed $value): bool
    {
        return match($this) {
            self::EQ => $actualValue === $value,
            self::NEQ => $actualValue !== $value,
        };
    }
}
