<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

/**
 * @coversDefaultClass Firehed\InputObjects\AnyOf
 * @covers ::<protected>
 * @covers ::<private>
 */
class AnyOfTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    protected function getInputObject()
    {
        return new AnyOf(
            new Literal(42),
            new Literal('forty two')
        );
    }

    public function evaluations()
    {
        return [
            [42, 42],
            ['forty two', 'forty two'],
        ];
    }

    public function invalidEvaluations()
    {
        return [
            [41],
            ['42'],
            [true],
            [false],
            [null],
            [[42]],
        ];
    }
}
