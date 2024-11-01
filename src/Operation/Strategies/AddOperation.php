<?php

declare(strict_types=1);

namespace QLParser\Operation\Strategies;

use QLParser\Utils\Arr;

class AddOperation implements OperationStrategyInterface
{
    /**
     * @param array      $data
     * @param string     $path
     * @param mixed|null $value
     *
     * @return void
     */
    public function apply(array &$data, string $path, mixed $value = null): void
    {
        $arrData = &Arr::getReference($data, $path);

        if (Arr::isArray($arrData) && !empty($arrData)) {
            if (Arr::isArray($value)) {
                if (Arr::isList($value)) {
                    foreach ($value as $item) {
                        $arrData[] = $item;
                    }
                } else {
                    foreach ($value as $key => $item) {
                        $arrData[$key] = $item;
                    }
                }
            } else {
                // Add new key-value pair to the array
                $arrData[] = $value;
            }
        } else {
            // Create a new array at the path if it's not an array
            Arr::set($data, $path, $value);
        }
    }
}
