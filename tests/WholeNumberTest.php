<?php

namespace Firehed\InputObjects;

/**
 * @covers Firehed\InputObjects\WholeNumber
 */
class WholeNumberTest extends \PHPUnit\Framework\TestCase
{
    public function testDeprecationWarning(): void
    {
        self::expectDeprecation();
        new WholeNumber();
    }
}
