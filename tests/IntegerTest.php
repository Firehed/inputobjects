<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\Integer
 * @covers ::<protected>
 * @covers ::<private>
 */
class IntegerTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    protected function getInputObject(): InputObject
    {
        return new Integer();
    }

    public function evaluations(): array
    {
        return [
            ['1', 1],
            ['1.0', 1],
            ['0.0', 0],
            ['-1.0', -1],
            ['-1', -1],
            [-50, -50],
            [0, 0],
            [50, 50],
        ];
    }

    public function invalidEvaluations(): array
    {
        return [
            ['1.1'],
            ['0.1'],
            ['-0.1'],
            ['0xEF'],
            ['1e1'],
            ['string'],
            [true],
            [false],
            [null],
            [-1.1],
            [-0.1],
            [0.1],
            [1.1],
        ];
    }
}
