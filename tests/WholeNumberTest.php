<?php

namespace Firehed\InputObjects;

/**
 * @coversDefaultClass Firehed\InputObjects\WholeNumber
 * @covers ::<protected>
 * @covers ::<private>
 */
class WholeNumberTest extends \PHPUnit\Framework\TestCase
{

    private $number;
    public function setUp()
    {
        $this->number = new WholeNumber();
    } // setUp

    // Used by:
    // testValidate
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
    } // validations

    public function evaluations()
    {
        return [
            ['123', 123],
            ['0555', 555],
        ];
    } // evaluations

    public function invalidEvaluations()
    {
        return [
            ['12.34'],
            ['-12.34'],
            ['0xFF'],
            ['0b00110011'],
            ['0no'],
            ['1e1'],
        ];
    }

    /**
     * @covers ::setMax
     * @covers ::setMin
     * @covers ::validate
     * @dataProvider validations
     */
    public function testValidate($min, $max, $value, $isValid)
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
    public function testEvaluate($input_value, $expected_output)
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
     * @expectedException UnexpectedValueException
     */
    public function testInvalidEvaliations($input_value)
    {
        $this->number->setValue($input_value)->evaluate();
    }
}
