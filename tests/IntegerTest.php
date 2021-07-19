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

    /**
     * @return array{mixed, int}[]
     */
    public function evaluations(): array
    {
        return [
            ['1', 1],
            ['1.0', 1],
            ['0.0', 0],
            ['-1.0', -1],
            ['-1', -1],
            ['0555', 555],
            [-50, -50],
            [0, 0],
            [50, 50],
        ];
    }

    /**
     * @return array{mixed}[]
     */
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

    // Used by:
    // testValidateWithRanges
    /**
     * @return array{?int, ?int, mixed, bool}[]
     */
    public function validations()
    {
        return [
            [null, null, '', false],
            [0, null, '', false],
            [0, null, '1', true],
            [0, null, '-1', false],
            [1, null, '', false],
            [null, 1, '', false],
            [null, 1, 'a', false],
            [null, 1, 'aa', false],
            [null, 0, '0', true],
            [null, 0, '1', false],
            [1, 1, '', false],
            [1, 1, 'a', false],
            [1, 1, 'aa', false],
            [1, 1, '1', true],
            [1, 1, '2', false],
            [1, 1, '0', false],
            [null, null, '1234', true],
            [null, null, '-1234', true],
            [null, null, '12.34', false],
            [null, null, '-12.34', false],
            [null, null, 'word', false],
            [null, null, '0555', true],
            [null, null, '1.3e7', false],
            [null, null, 1234, true],
            [null, null, true, false],
            [null, null, false, false],
            [null, null, null, false],
        ];
    }

    /**
     * @covers ::setMax
     * @covers ::setMin
     * @covers ::validate
     * @dataProvider validations
     * @param mixed $value
     */
    public function testValidateWithRanges(?int $min, ?int $max, $value, bool $isValid): void
    {
        /** @var \Firehed\InputObjects\Integer */
        $int = $this->getInputObject();
        if ($min !== null) {
            $int->setMin($min);
        }
        if ($max !== null) {
            $int->setMax($max);
        }
        $int->setValue($value);
        self::assertSame(
            $isValid,
            $int->isValid(),
            'Validation did not match expected output'
        );
    }
}
