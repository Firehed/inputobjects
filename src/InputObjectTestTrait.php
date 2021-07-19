<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use UnexpectedValueException;

/**
 * Trait that can be used in any PHPUnit TestCase
 */
trait InputObjectTestTrait
{
    /**
     * Provide the input object to test
     */
    abstract protected function getInputObject(): InputObject;

    /**
     * Data provider for testValidate and testEvaluate.
     *
     * Should return an array of [input, expected_output]
     *
     * @return array{mixed, mixed}
     */
    abstract public function evaluations(): array;

    /**
     * Data provider for testInvalidEvaluations.
     *
     * Should return an arrat of [invalid_input]
     *
     * @return array{mixed}
     */
    abstract public function invalidEvaluations(): array;

    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        self::assertInstanceOf(
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
        self::assertTrue(
            $inputObject->isValid(),
            'Validation did not pass'
        );
    }

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
            self::assertEquals(
                $expected_output,
                $inputObject->setValue($input_value)->evaluate(),
                'Evaluated value did not match the expected output'
            );
        } else {
            self::assertSame(
                $expected_output,
                $inputObject->setValue($input_value)->evaluate(),
                'Evaluated value did not match the expected output'
            );
        }
    }

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
