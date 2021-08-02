<?php

namespace Firehed\InputObjects;

/**
 * @coversDefaultClass Firehed\InputObjects\WholeNumber
 * @covers ::<protected>
 * @covers ::<private>
 */
class WholeNumberTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers ::__construct
     */
    public function testDeprecationWarning(): void
    {
        self::expectDeprecation();
        new WholeNumber();
    }
}
