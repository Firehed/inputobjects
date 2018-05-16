<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * A validator to allow any data through. Use of this is generally discouraged
 * for day-to-day use, but helps support some edge cases.
 */
class Any extends InputObject
{
    public function validate($value): bool
    {
        return true;
    }
}
