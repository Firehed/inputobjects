<?php

namespace Firehed\InputObjects;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * @coversDefaultClass Firehed\InputObjects\Number
 * @covers ::<protected>
 * @covers ::<private>
 */
class NumberTest extends \PHPUnit\Framework\TestCase
{

    private $number;
    public function setUp(): void
    {
        $this->number = new Number();
    } // setUp

    // Used by:
    // testInvalidMin
    // testInvalidMax
    public function invalidRangeValues()
    {
        return [
            [null],
            [false],
            [true],
            ["1.1"],
            ["NAN"],
            ["-2"],
            ["three"],
            [""],
        ];
    } // invalidRangeValues

    // Used by:
    // testValidate
    public function validations(): array
    {
        return [
            [null, null, '', false],
            [0, null, '', false],
            [0, null, '1', true],
            [0, null, '0.5', true],
            [0, null, '-1', false],
            [1, null, '', false],
            [null, 1, '', false],
            [null, 1, '0', true],
            [null, 1, '0.5', true],
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
            [null, null, 'word', false],
            [null, null, '0555', true],
            [null, null, '1.3e7', false],
            [null, null, '0xFF', false],
            [null, null, '0b00001111', false],
            [null, null, 1234, true],
            [null, null, true, false],
            [null, null, false, false],
            [null, null, null, false],
        ];
    } // validations

    public function evaluations(): array
    {
        return [
            ['123', 123],
            ['123.0', (float)123],
            ['0.4', 0.4],
            ['.4', 0.4],
            ['-123', -123],
            ['-123.4', -123.4],
            ['0555', 555], // We trim leading zeroes, not treat as octal
        ];
    } // evaluations

    public function invalidEvaluations(): array
    {
        return [
            ['0xFF'],
            ['0b00110011'],
            ['0no'],
            ['1e2'],
        ];
    }

    // Used by:
    // testValidMax
    // testValidMin
    public function validRangeValues(): array
    {
        return [
            [1],
            [200],
            [255],
            [256],
            [0],
            [-1],
            [-200],
            [-255],
            [-256],
        ];
    } // validRangeValues

    // Used by:
    // testValidMaxMinCombinations
    public function validRangePairs(): array
    {
        return [
            [10, 5],
            [10, 10],
            [100, 0],
            [\PHP_INT_MAX, 0],
        ];
    } // validRangePairs

    /**
     * @covers ::setMax
     * @dataProvider invalidRangeValues
     */
    public function testInvalidMax($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->number->setMax($value);
    } // testInvalidMax

    /**
     * @covers ::setMax
     * @covers ::validate
     * @dataProvider validRangeValues
     */
    public function testValidMax($value): void
    {
        $this->assertSame(
            $this->number,
            $this->number->setMax($value),
            'setMax should be chainable when called with a valid value'
        );
    } // testValidMax

    /**
     * @covers ::setMin
     * @dataProvider invalidRangeValues
     */
    public function testInvalidMin($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->number->setMin($value);
    } // testInvalidMin

    /**
     * @covers ::setMin
     * @covers ::validate
     * @dataProvider validRangeValues
     */
    public function testValidMin($value): void
    {
        $this->assertSame(
            $this->number,
            $this->number->setMin($value),
            'setMin should be chainable when called with a valid value'
        );
    } // testValidMin


    /**
     * @covers ::setMax
     * @covers ::setMin
     */
    public function testIncompatibleMaxAfterMin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->number->setMin(5)
            ->setMax(4);
    } // testIncompatibleMaxAfterMin

    /**
     * @covers ::setMax
     * @covers ::setMin
     */
    public function testIncompatibleMinAfterMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->number->setMax(4)
            ->setMin(5);
    } // testIncompatibleMinAfterMax

    /**
     * @covers ::setMax
     * @covers ::setMin
     * @dataProvider validRangePairs
     */
    public function testValidMaxMinCombinations($max, $min): void
    {
        $this->assertSame(
            $this->number,
            $this->number->setMax($max)->setMin($min),
            'Specified max and min should have been compatible'
        );
    } // testValidMaxMinCombinations

    /**
     * @covers ::setMax
     * @covers ::setMin
     * @covers ::validate
     * @dataProvider validations
     */
    public function testValidate($min, $max, $value, $isValid): void
    {
        if ($min !== null) {
            $this->number->setMin($min);
        }
        if ($max !== null) {
            $this->number->setMax($max);
        }
        $this->number->setValue($value);
        $this->assertSame(
            $isValid,
            $this->number->isValid(),
            'Validation did not match expected output'
        );
    } // testValidate

    /**
     * @covers ::evaluate
     * @dataProvider evaluations
     */
    public function testEvaluate($input_value, $expected_output): void
    {
        $this->assertSame(
            $expected_output,
            $this->number->setValue($input_value)->evaluate(),
            'Evaluated value did not match the expected output'
        );
    } // testEvaluate

    /**
     * @covers ::evaluate
     * @dataProvider invalidEvaluations
     */
    public function testInvalidEvaliations($input_value): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->number->setValue($input_value)->evaluate();
    }
}
