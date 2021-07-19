<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\AnyOf
 * @covers ::<protected>
 * @covers ::<private>
 */
class AnyOfTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    protected function getInputObject(): InputObject
    {
        return new AnyOf(
            new Literal(42),
            new Literal('forty two')
        );
    }

    public function evaluations(): array
    {
        return [
            [42, 42],
            ['forty two', 'forty two'],
        ];
    }

    public function invalidEvaluations(): array
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

    public function testOptionalListOf(): void
    {
        $enum = new Enum(['a', 'b', 'c']);
        $io = new AnyOf($enum, new ListOf($enum));
        $input = ['c', 'b'];
        self::assertTrue($io->setValue($input)->isValid());
        self::assertSame($input, $io->setValue($input)->evaluate());
    }
}
