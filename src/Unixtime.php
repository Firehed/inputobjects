<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use DateTimeImmutable;

/**
 * Takes a number input, treats it as a Unix timestamp, and evaluates into
 * a DateTimeImmutable object. Clients SHOULD provide sub-second resolution
 * timestamps as a string rather than actual float to avoid precision issues.
 */
class Unixtime extends Number
{

    public function __construct(bool $defaultToNow = false)
    {
        parent::__construct();
        if ($defaultToNow) {
            $this->setDefaultValue(new DateTimeImmutable());
        }
    }

    public function evaluate()
    {
        $timestamp = $this->getValue();
        // This logic is a little janky because floating point precision issues
        // come up in the billion range. What this basically ends up implying
        // is that if you want to use fractional unixtime, pass the value as
        // a string (this only comes up in JSON and other typed input methods
        // that can actually submit an actual number type. Any
        // second-resolution timestamp should be fine.
        //
        // This does _not_ call the parent evaluation method to avoid casting
        // to float and creating precision issues.
        if ((int)$timestamp == $timestamp) {
            $format = 'U';
        } else {
            $format = 'U.u';
        }
        return DateTimeImmutable::createFromFormat($format, (string) $timestamp);
    }
}
