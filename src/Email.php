<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Email extends InputObject
{

    protected function validate($value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }

}
