<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use InvalidArgumentException;

class Text extends InputObject
{

    private $min = null;
    private $max = null;
    private $trim = false;

    public function setMin($min): self
    {
        if (!is_int($min)) {
            throw new InvalidArgumentException(
                "Integer required"
            );
        }
        if ($min < 0) {
            throw new InvalidArgumentException(
                "Minimum cannot be less than zero"
            );
        }

        if (null !== $this->max && $this->max < $min) {
            throw new InvalidArgumentException(
                "Minimum cannot be greater than maximum"
            );
        }

        $this->min = $min;
        return $this;
    } // setMin

    public function setMax($max): self
    {
        if (!is_int($max)) {
            throw new InvalidArgumentException(
                "Integer required"
            );
        }
        if ($max <= 0) {
            throw new InvalidArgumentException(
                "Maximum cannot be less than one"
            );
        }
        if (null !== $this->min && $this->min > $max) {
            throw new InvalidArgumentException(
                "Maximum cannot be less than minimum"
            );
        }
        $this->max = $max;
        return $this;
    } // setMax

    public function setTrim(bool $shouldTrim): self
    {
        $this->trim = $shouldTrim;
        return $this;
    }

    protected function validate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        if ($this->trim) {
            $value = trim($value);
        }
        if (null !== $this->min) {
            if (strlen($value) < $this->min) {
                return false;
            }
        }
        if (null !== $this->max) {
            if (strlen($value) > $this->max) {
                return false;
            }
        }
        return true;
    }

    public function evaluate()
    {
        $value = $this->getValue();
        if ($this->trim) {
            return trim($value);
        }
        return $value;
    }
}
