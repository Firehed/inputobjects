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

    public function setUp(): void
    {
        $this->concrete = $this->createMock(InputObject::class);
        $this->nullable = new Nullable($this->concrete);
    }

    /** @covers ::__construct */
    public function testConstruct(): void
    {
        self::assertInstanceOf(
            InputObject::class,
            $this->nullable
        );
    }

    /**
     * @covers ::validate
     * @covers ::evaluate
     */
    public function testValidateWithNull(): void
    {
        $this->nullable->setValue(null);
        $this->concrete
            ->expects($this->never())
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

    /**
     * @covers ::validate
     * @covers ::evaluate
     */
    public function testEvaluateWithValue(): void
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
            ->expects($this->atLeastOnce())
            ->method('validate')
            ->with($value)
            ->willReturn(false);
        self::assertFalse(
            $this->nullable->isValid(),
            'Validation should not have passed'
        );
     }

}
