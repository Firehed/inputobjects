<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\Literal
 * @covers ::<protected>
 * @covers ::<private>
 */
class LiteralTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    protected function getInputObject(): InputObject
    {
        return new Literal('some string');
    }

    public function evaluations(): array
    {
        return [
            ['some string', 'some string'],
        ];
    }

    public function invalidEvaluations(): array
    {
        return [
            ['some string '],
            [' some string'],
            [' some string'],
            ['Some string'],
            ['SOME STRING'],
            [42],
            [42.42],
            [true],
            [false],
            [null],
            [[]],
            [['some string']],
        ];
    }
}
