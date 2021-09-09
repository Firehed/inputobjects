<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @covers Firehed\InputObjects\Nullable
 */
class NullableTest extends \PHPUnit\Framework\TestCase
{
    /** @var InputObject & \PHPUnit\Framework\MockObject\MockObject */
    private InputObject $concrete;

    private Nullable $nullable;

    public function setUp(): void
    {
        $this->concrete = $this->createMock(InputObject::class);
        $this->nullable = new Nullable($this->concrete);
    }

    public function testConstruct(): void
    {
        self::assertInstanceOf(
            InputObject::class,
            $this->nullable
        );
    }

    public function testValidateWithNull(): void
    {
        $this->nullable->setValue(null);
        $this->concrete
            ->expects(self::never())
            ->method('validate');
        self::assertTrue(
            $this->nullable->isValid(),
            'Validation should have passed'
        );
        self::assertNull(
            $this->nullable->evaluate(),
            'Evaluate should have returned null'
        );
    }

    public function testEvaluateWithValue(): void
    {
        $value = random_int(0, PHP_INT_MAX);
        $this->nullable->setValue($value);
        $this->concrete
            ->expects(self::atLeastOnce())
            ->method('validate')
            ->with($value)
            ->willReturn(true);
         $this->concrete
            ->expects(self::atLeastOnce())
            ->method('evaluate')
            ->willReturn($value);
        self::assertTrue(
            $this->nullable->isValid(),
            'Validation should have passed'
        );
        self::assertSame(
            $value,
            $this->nullable->evaluate(),
            'Evaluate should have returned the value'
        );
    }

    public function testInvalidEvaluation(): void
    {
        $value = random_int(0, PHP_INT_MAX);
        $this->nullable->setValue($value);
        $this->concrete
            ->expects(self::atLeastOnce())
            ->method('validate')
            ->with($value)
            ->willReturn(false);
        self::assertFalse(
            $this->nullable->isValid(),
            'Validation should not have passed'
        );
    }
}
