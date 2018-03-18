<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Containers\ParsedInput;
use Firehed\Input\Exceptions\InputException;
use Firehed\Input\Interfaces\ValidationInterface;
use Firehed\Input\Objects\InputObject;

abstract class Structure extends InputObject implements
    ValidationInterface
{
    private $validated;

    protected function validate($value): bool
    {
        if (!is_array($value)) {
            return false;
        }
        $parsed = new ParsedInput($value);
        $this->validated = $parsed->validate($this);
        // If validation fails, an exception will be thrown that matches the
        // same format expected elsewhere, so there's no need to handle it here
        return true;
    } // validate

    public function evaluate()
    {
        // Performs validation
        parent::evaluate();
        return $this->validated->asArray();
    } // evaluate
}
