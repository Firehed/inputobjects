<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use DateTime;
use DateTimeImmutable;
use Firehed\Input\Objects\InputObject;

class DateTimeString extends InputObject
{
    public function validate($value): bool
    {
        return $value !== null && strtotime($value) !== false;
    }

    public function evaluate()
    {
        $value = $this->getValue();
        return new DateTimeImmutable($value);
    }
}
