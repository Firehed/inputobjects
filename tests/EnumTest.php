<?php

namespace Firehed\InputObjects;

/**
 * @coversDefaultClass Firehed\InputObjects\Enum
 * @covers ::<protected>
 * @covers ::<private>
 */
class EnumTest extends \PHPUnit\Framework\TestCase
{

    public function testValidValue()
    {
        $fixture = $this->getFixture();
        $fixture->setValue('hello');
        $this->assertTrue($fixture->isValid(), 'Should have been valid');
        $this->assertSame(
            'hello',
            $fixture->evaluate(),
            'The wrong value was returned from evaluate'
        );
    } // testValidValue

    public function testInvalidValue()
    {
        $fixture = $this->getFixture();
        $fixture->setValue('hola');
        $this->assertFalse($fixture->isValid(), 'Should have been invalid');
    } // testInvalidValues

    private function getFixture(): Enum
    {
        return new class extends Enum
        {
            protected function getValidValues(): array
            {
                return [
                    'hello',
                    'goodbye',
                ];
            }
        };
    }
}
