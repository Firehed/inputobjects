<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

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
     * Data provider for testInvalidEvaliations.
     *
     * Should return an arrat of [invalid_input]
     *
     * @return array
     */
    abstract public function invalidEvaluations(): array;

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            InputObject::class,
            $this->getInputObject()
        );
    }

    /**
     * @covers ::validate
     * @dataProvider evaluations
     */
    public function testValidate($inputValue)
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
     */
    public function testEvaluate($input_value, $expected_output)
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
     * @expectedException UnexpectedValueException
     */
    public function testInvalidEvaliations($input_value)
    {
        $inputObject = $this->getInputObject();
        $inputObject->setValue($input_value)->evaluate();
    }
}
