<?php

namespace Firehed\InputObjects;

use InvalidArgumentException;

/**
 * @coversDefaultClass Firehed\InputObjects\Text
 * @covers ::<protected>
 * @covers ::<private>
 */
class TextTest extends \PHPUnit\Framework\TestCase
{
    private Text $text;

    public function setUp(): void
    {
        $this->text = new Text;
    }

    // Used by:
    // testValidate
    /**
     * @return array{?int, ?int, mixed, bool}[]
     */
    public function validations(): array
    {
        return [
            [null, null, '', true],
            [0, null, '', true],
            [1, null, '', false],
            [null, 1, '', true],
            [null, 1, 'a', true],
            [null, 1, 'aa', false],
            [1, 1, '', false],
            [1, 1, 'a', true],
            [1, 1, 'aa', false],
            [null, null, '1234', true],
            [null, null, 'word', true],
            [null, null, '0555', true],
            [null, null, '1.3e7', true],
            [null, 6, '1.3e9', true], // watch for weird number evaluation
            [null, null, 1234, false],
            [null, null, true, false],
            [null, null, false, false],
            [null, null, null, false],
        ];
    } // validations

    // Used by:
    // testValidMax
    // testValidMin
    /**
     * @return array{int}[]
     */
    public function validRangeValues(): array
    {
        return [
            [1],
            [200],
            [255],
            [256],
            [\PHP_INT_MAX]
        ];
    } // validRangeValues

    // Used by:
    // testValidMaxMinCombinations
    /**
     * @return array{int, int}[]
     */
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
     * @covers ::validate
     * @dataProvider validRangeValues
     */
    public function testValidMax(int $value): void
    {
        self::assertSame(
            $this->text,
            $this->text->setMax($value),
            'setMax should be chainable when called with a valid value'
        );
    } // testValidMax

    /**
     * @covers ::setMin
     * @covers ::validate
     * @dataProvider validRangeValues
     */
    public function testValidMin(int $value): void
    {
        self::assertSame(
            $this->text,
            $this->text->setMin($value),
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
        $this->text->setMin(5)
            ->setMax(4);
    } // testIncompatibleMaxAfterMin

    /**
     * @covers ::setMax
     * @covers ::setMin
     */
    public function testIncompatibleMinAfterMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->text->setMax(4)
            ->setMin(5);
    } // testIncompatibleMinAfterMax

    /**
     * @covers ::setMax
     * @covers ::setMin
     * @dataProvider validRangePairs
     */
    public function testValidMaxMinCombinations(int $max, int $min): void
    {
        self::assertSame(
            $this->text,
            $this->text->setMax($max)->setMin($min),
            'Specified max and min should have been compatible'
        );
    } // testValidMaxMinCombinations

    /**
     * @covers ::setMax
     */
    public function testMaxOfZeroIsDisallowed(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->text->setMax(0);
    } // testMaxOfZeroIsDisallowed

    /**
     * @covers ::setMin
     */
    public function testMinOfZeroIsAllowed(): void
    {
        self::assertSame(
            $this->text,
            $this->text->setMin(0),
            'SetMin should allow 0'
        );
    } // testMinOfZeroIsAllowed

    /**
     * @covers ::setTrim
     */
    public function testSetTrimReturnsThis(): void
    {
        self::assertSame(
            $this->text,
            $this->text->setTrim(true),
            'setTrim should return $this'
        );
    }

    /**
     * @covers ::evaluate
     * @covers ::setTrim
     */
    public function testTrimDefaultsToFalse(): void
    {
        $input = ' text with trailing space ';
        self::assertSame(
            $input,
            $this->text->setValue($input)->evaluate(),
            'Trailing space should not have been trimmed'
        );
    }

    /**
     * @covers ::evaluate
     * @covers ::setTrim
     */
    public function testTrimWorksWhenEnabled(): void
    {
        $input = ' text with trailing space ';
        $output = 'text with trailing space';
        self::assertSame(
            $output,
            $this->text->setTrim(true)->setValue($input)->evaluate(),
            'Trailing space should have been trimmed'
        );
    }

    /**
     * @covers ::evaluate
     * @covers ::setTrim
     */
    public function testTrimAllowsExplicitFalse(): void
    {
        $input = ' text with trailing space ';
        self::assertSame(
            $input,
            $this->text->setTrim(false)->setValue($input)->evaluate(),
            'Trailing space should not have been trimmed'
        );
    }

    /**
     * @covers ::validate
     */
    public function testTrimInteractionWithSetMin(): void
    {
        $input = ' ';
        self::assertFalse(
            $this->text->setTrim(true)->setMin(1)->setValue($input)->isValid(),
            'Only space should not validate with trim enabled and a minimum'
        );
    }


    /**
     * @covers ::setMax
     * @covers ::setMin
     * @covers ::evaluate
     * @covers ::validate
     * @dataProvider validations
     * @param mixed $value
     */
    public function testValidate(?int $min, ?int $max, $value, bool $isValid): void
    {
        if ($min !== null) {
            $this->text->setMin($min);
        }
        if ($max !== null) {
            $this->text->setMax($max);
        }
        $this->text->setValue($value);
        self::assertSame(
            $isValid,
            $this->text->isValid(),
            'Validation did not match expected output'
        );
        if ($isValid) {
            // Valid values should just pass straight through
            self::assertSame(
                $value,
                $this->text->evaluate(),
                'Evaluate returned the wrong value'
            );
        }
    } // testValidate
}
