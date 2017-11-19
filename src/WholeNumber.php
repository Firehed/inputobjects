<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

/**
 * Define an Integer input class (called WholeNumber since int and integer are
 * becoming reserved keywords in PHP7)
 *
 * Works like Number, but rejects non-integer values
 */
class WholeNumber extends Number
{
    protected function validate($value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }
        $intval = (int)$value;
        return $intval == $value;
    }

}