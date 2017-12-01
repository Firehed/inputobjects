<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Enum extends InputObject
{
    /** @var string[] */
    private $validValues;

    public function __construct(array $values)
    {
        parent::__construct();
        $this->validValues = $values;
    }

    /**
     * @param mixed $value value to validate
     * @return bool
     */
    final protected function validate($value): bool
    {
        return in_array($value, $this->validValues, true);
    } // validate
}
