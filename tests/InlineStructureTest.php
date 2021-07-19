<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\InlineStructure
 * @covers ::<protected>
 * @covers ::<private>
 */
class InlineStructureTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    protected function getInputObject(): InputObject
    {
        return new InlineStructure([
            'r1' => new Boolean(),
            'r2' => new Integer(),
        ], [
            'o1' => new Text(),
            'o2' => new Email(),
        ]);
    }

    /**
     * @return array{
     *   array{r1: bool, r2: int, o1?: string, o2?: string},
     *   array{r1: bool, r2: int, o1: ?string, o2: ?string},
     * }[]
     */
    public function evaluations(): array
    {
        return [
            [
                ['r1' => true, 'r2' => 2, 'o1' => 'o', 'o2' => 'e@example.com'],
                ['r1' => true, 'r2' => 2, 'o1' => 'o', 'o2' => 'e@example.com'],
            ],
            [
                ['r1' => true, 'r2' => 2, 'o1' => 'o'],
                ['r1' => true, 'r2' => 2, 'o1' => 'o', 'o2' => null],
            ],
            [
                ['r1' => true, 'r2' => 2, 'o2' => 'e@example.com'],
                ['r1' => true, 'r2' => 2, 'o1' => null, 'o2' => 'e@example.com'],
            ],
            [
                ['r1' => true, 'r2' => 2],
                ['r1' => true, 'r2' => 2, 'o1' => null, 'o2' => null],
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function invalidEvaluations(): array
    {
        return [
                ['r1' => true, 'r2' => 2, 'o1' => null, 'o2' => 'notanemail'],
                ['r1' => true, 'r2' => 2, 'o1' => 'o', 'o2' => 'e@example.com'],
                ['r1' => true, 'r2' => 2.1],
                ['r1' => null, 'r2' => 2],
        ];
    }
}
