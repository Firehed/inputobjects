<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Uuid extends InputObject
{
    private static $versions = [1, 3, 4, 5];

    public function validate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        $regex = '#^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$#i
';
        if (!preg_match($regex, $value)) {
            return false;
        }
        $version = $value[14];

        if ($version === '0') {
            return $value === '00000000-0000-0000-0000-000000000000';
        }
        // Future: examine the variant (ord($value[19]) & 0xF0) and
        // do...something
        return in_array($version, self::$versions);
    }

    public function evaluate()
    {
        return strtolower($this->getValue());
    }
}
