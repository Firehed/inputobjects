<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

/**
 * Define an Integer input class
 *
 * Works like Number, but rejects non-integer values
 */
class Integer extends Number
{
    protected function validate($value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }
        $intval = (int)$value;
        return $intval == $value;
    }

    public function evaluate()
    {
        return (int) $this->getValue();
    }
}
