<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use InvalidArgumentException;
use Firehed\Input\Objects\InputObject;

class Number extends InputObject
{
    /** @var ?float */
    private $min = null;

    /** @var ?float */
    private $max = null;

    public function setMin(float $min): self
    {
        if (null !== $this->max && $this->max < $min) {
            throw new InvalidArgumentException(
                "Minimum cannot be greater than maximum"
            );
        }

        $this->min = $min;
        return $this;
    }

    public function setMax(float $max): self
    {
        if (null !== $this->min && $this->min > $max) {
            throw new InvalidArgumentException(
                "Maximum cannot be less than minimum"
            );
        }
        $this->max = $max;
        return $this;
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        // In a true PHP-ism, two number-like strings will be compared as
        // numbers when using loose equality (e.g. "1e2" == "100"). Using
        // strict instead also won't work because it breaks inputs like "04" or
        // "123.0". So we're going to take the stupid approach, and simply
        // ensure the input value contains only number characters. ctype_digit
        // blocks decimal points and negative signs, and there's no practical
        // way to do the check with casting alone.
        $value = (string)$value;
        // @phpstan-ignore-next-line
        if (!preg_match('/^-?[0-9]*\.?[0-9]+$/', $value)) {
            return false;
        }
        $value = (float)$value;
        if (null !== $this->min) {
            if ($value < $this->min) {
                return false;
            }
        }
        if (null !== $this->max) {
            if ($value > $this->max) {
                return false;
            }
        }
        return true;
    }

    public function evaluate()
    {
        return $this->getValue() + 0;
    }
}
