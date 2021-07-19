<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\Boolean
 * @covers ::<protected>
 * @covers ::<private>
 */
class BooleanTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    protected function getInputObject(): InputObject
    {
        return new Boolean();
    }

    /**
     * @return array{mixed, bool}[]
     */
    public function evaluations(): array
    {
        return [
            // Boolean literal
            [false, false],
            [true, true],
            // Boolean in string format
            ['false', false],
            ['true', true],
            // Boolean as numeric string
            ['0', false],
            ['1', true],
            // Boolean as integer literal
            [0, false],
            [1, true],
        ];
    }

    /**
     * @return array{mixed}
     */
    public function invalidEvaluations(): array
    {
        return [
            [1.5],
            [null],
            [-1],
            ['ture'],
            ['flase'],
            [2],
            ['2'],
            ['1.5'],
            ['0.0'],
            ['-0'],
            ['0.1'],
            ['0.6'],
            ['0.9'],
            ['-0.0'],
            ['-0.1'],
            [''],
        ];
    }
}
