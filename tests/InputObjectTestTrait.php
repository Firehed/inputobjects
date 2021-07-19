<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use UnexpectedValueException;

trait InputObjectTestTrait
{
    /**
     * Provide the input object to test
     *
     * @return \Firehed\Input\Objects\InputObject
     */
    abstract protected function getInputObject(): InputObject;

    /**
     * Data provider for testValidate and testEvaluate.
     *
     * Should return an array of [input, expected_output]
     *
     * @return array
     */
    abstract public function evaluations(): array;

    /**
     * Data provider for testInvalidEvaluations.
     *
     * Should return an arrat of [invalid_input]
     *
     * @return array
     */
    abstract public function invalidEvaluations(): array;

    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            InputObject::class,
            $this->getInputObject()
        );
    }

    /**
     * @covers ::validate
     * @dataProvider evaluations
     * @param mixed $inputValue
     */
    public function testValidate($inputValue): void
    {
        $inputObject = $this->getInputObject();
        $inputObject->setValue($inputValue);
        $this->assertTrue(
            $inputObject->isValid(),
            'Validation did not pass'
        );
    } // testValidate

    /**
     * @covers ::evaluate
     * @dataProvider evaluations
     * @param mixed $input_value
     * @param mixed $expected_output
     */
    public function testEvaluate($input_value, $expected_output): void
    {
        $inputObject = $this->getInputObject();
        if (is_object($expected_output)) {
            $assert = [$this, 'assertEquals'];
        } else {
            $assert = [$this, 'assertSame'];
        }
        $assert(
            $expected_output,
            $inputObject->setValue($input_value)->evaluate(),
            'Evaluated value did not match the expected output'
        );
    } // testEvaluate

    /**
     * @covers ::evaluate
     * @covers ::validate
     * @dataProvider invalidEvaluations
     * @param mixed $input_value
     */
    public function testInvalidEvaluations($input_value): void
    {
        $inputObject = $this->getInputObject();
        $this->expectException(UnexpectedValueException::class);
        $inputObject->setValue($input_value)->evaluate();
    }
}
