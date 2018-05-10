<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Any extends InputObject
{
    public function validate($value): bool
    {
        return true;
    }
}
