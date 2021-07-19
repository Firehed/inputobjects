<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Uuid extends InputObject
{
    /** @var int[] */
    private static $versions = [1, 3, 4, 5];

    /**
     * @param mixed $value
     */
    public function validate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        $regex = '#^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$#i
';
        // @phpstan-ignore-next-line
        if (!preg_match($regex, $value)) {
            return false;
        }
        $version = (int)$value[14];

        if ($version === 0) {
            return $value === '00000000-0000-0000-0000-000000000000';
        }
        // Future: examine the variant (ord($value[19]) & 0xF0) and
        // do...something
        return in_array($version, self::$versions, true);
    }

    public function evaluate()
    {
        return strtolower($this->getValue());
    }
}
