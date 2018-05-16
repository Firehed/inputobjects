<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\Nullable
 * @covers ::<protected>
 * @covers ::<private>
 */
class NullableTest extends \PHPUnit\Framework\TestCase
{
    /** @var InputObject */
    private $concrete;

    /** @var Nullable */
    private $nullable;

    public function setUp()
    {
        $this->concrete = $this->createMock(InputObject::class);
        $this->nullable = new Nullable($this->concrete);
    }

    /** @covers ::__construct */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            InputObject::class,
            $this->nullable
        );
    }

    /**
     * @covers ::validate
     * @covers ::evaluate
     */
    public function testValidateWithNull()
    {
        $this->nullable->setValue(null);
        $this->concrete
            ->expects($this->never())
            ->method('validate');
        $this->assertTrue(
            $this->nullable->isValid(),
            'Validation should have passed'
        );
        $this->assertNull(
            $this->nullable->evaluate(),
            'Evaluate should have returned null'
        );
    }

    /**
     * @covers ::validate
     * @covers ::evaluate
     */
    public function testEvaluateWithValue()
    {
        $value = random_int(0, PHP_INT_MAX);
        $this->nullable->setValue($value);
        $this->concrete
            ->expects($this->atLeastOnce())
            ->method('validate')
            ->with($value)
            ->willReturn(true);
         $this->concrete
            ->expects($this->atLeastOnce())
            ->method('evaluate')
            ->willReturn($value);
        $this->assertTrue(
            $this->nullable->isValid(),
            'Validation should have passed'
        );
        $this->assertSame(
            $value,
            $this->nullable->evaluate(),
            'Evaluate should have returned the value'
        );
    }

    public function testInvalidEvaluation()
    {
        $value = random_int(0, PHP_INT_MAX);
        $this->nullable->setValue($value);
        $this->concrete
            ->expects($this->atLeastOnce())
            ->method('validate')
            ->with($value)
            ->willReturn(false);
        $this->assertFalse(
            $this->nullable->isValid(),
            'Validation should not have passed'
        );
     }

}
