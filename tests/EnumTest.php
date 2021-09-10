<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @covers Firehed\InputObjects\Enum
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

    /**
     * @return array{mixed, mixed}[]
     */
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

    /**
     * @return array{mixed}[]
     */
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
