<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

abstract class Enum extends InputObject
{

    /**
     * @param mixed $value value to validate
     * @return bool
     */
    final protected function validate($value): bool
    {
        return in_array($value, $this->getValidValues());
    } // validate

    /**
     * @return array<string>
     */
    abstract protected function getValidValues(): array;
}
