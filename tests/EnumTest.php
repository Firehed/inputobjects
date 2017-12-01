<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\Enum
 * @covers ::<protected>
 * @covers ::<private>
 */
class EnumTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    public function getInputObject(): InputObject
    {
        return new Enum([
            'hello',
            'goodbye',
            10,
            5.5,
            true
        ]);
    }

    public function evaluations(): array
    {
        return [
            ['hello', 'hello'],
            ['goodbye', 'goodbye'],
            [10, 10],
            [5.5, 5.5],
            [true, true],
        ];
    }

    public function invalidEvaluations(): array
    {
        return [
            ['hola'],
            ['10'],
            ['10.0'],
            [(float) 10],
            ['5.5'],
            ['true'],
            [1],
            [null],
            [false],
        ];
    }
}
