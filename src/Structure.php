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
        try {
            $this->validated = $parsed->validate($this);
            return true;
        }
        catch (InputException $e) {
            return false;
        }
    } // validate

    public function evaluate()
    {
        // Performs validation
        parent::evaluate();
        return $this->validated->asArray();
    } // evaluate

}
