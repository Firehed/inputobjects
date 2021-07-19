<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use InvalidArgumentException;

class Boolean extends InputObject
{
    /**
     * @param mixed $value
     */
    protected function validate($value): bool
    {
        if (is_bool($value)) {
            return true;
        }

        if (is_string($value)) {
            if (
                $value === '1'
                || $value === '0'
                || $value === 'true'
                || $value === 'false'
            ) {
                return true;
            }
            return false;
        }

        if (is_int($value)) {
            return $value === 1 || $value === 0;
        }

        return false;
    }

    public function evaluate()
    {
        // This isn't handled by a simple boolean cast
        if ($this->getValue() === 'false') {
            return false;
        }
        return (bool) $this->getValue();
    }
}
