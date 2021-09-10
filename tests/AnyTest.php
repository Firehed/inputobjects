<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @covers Firehed\InputObjects\Any
 */
class AnyTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    protected function getInputObject(): InputObject
    {
        return new Any();
    }

    /**
     * @return array{mixed, mixed}[]
     */
    public function evaluations(): array
    {
        return array_map(function ($any): array {
            return [$any, $any];
        }, [
            null,
            true,
            false,
            -1,
            -0.5,
            0,
            0.5,
            1,
            'text',
            ['array', 'of', 'text'],
            ['dictionary' => 'value'],
        ]);
    }

    /**
     * @return never
     */
    public function invalidEvaluations(): array
    {
        self::markTestSkipped('It should be impossible to make this fail validation');
    }
}
