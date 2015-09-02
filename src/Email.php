<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Email extends InputObject
{

    protected function validate($value)
    {

        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }

}
