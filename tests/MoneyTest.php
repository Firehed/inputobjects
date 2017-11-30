<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Money\Currency;
use Money\Money as BaseMoney;

/**
 * @coversDefaultClass Firehed\InputObjects\Money
 * @covers ::<protected>
 * @covers ::<private>
 */
class MoneyTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers ::evaluate
     */
    public function testEvaluateReturnsMoney()
    {
        $money = new Money();
        $money->setValue(['amount' => 50, 'currency' => 'XTS']);
        $this->assertTrue($money->isValid());
        $obj = $money->evaluate();
        $this->assertInstanceOf(BaseMoney::class, $obj);
        $this->assertSame('50', $obj->getAmount());
        $this->assertEquals(
            new Currency('XTS'),
            $obj->getCurrency()
        );
    }


    /**
     * @covers ::evaluate
     */
    public function testValidNegativeAmount()
    {
        $money = new Money();
        $money->setValue(['amount' => -50, 'currency' => 'XTS']);
        $this->assertTrue($money->isValid());
        $obj = $money->evaluate();
        $this->assertInstanceOf(BaseMoney::class, $obj);
        $this->assertSame('-50', $obj->getAmount());
        $this->assertEquals(
            new Currency('XTS'),
            $obj->getCurrency()
        );
    }

    /**
     * @covers ::evaluate
     * @expectedException UnexpectedValueException
     */
    public function testFractionalAmount()
    {
        $money = new Money();
        $money->setValue(['amount' => 5.5, 'currency' => 'XTS']);
        $this->assertFalse($money->isValid());
        $obj = $money->evaluate();
    }

    /**
     * @covers ::evaluate
     * @expectedException UnexpectedValueException
     */
    public function testInvalidAmount()
    {
        $money = new Money();
        $money->setValue(['amount' => '0lol', 'currency' => 'XTS']);
        $this->assertFalse($money->isValid());
        $obj = $money->evaluate();
    }

    /**
     * @covers ::evaluate
     * @expectedException UnexpectedValueException
     */
    public function testInvalidCurrency()
    {
        $money = new Money();
        $money->setValue(['amount' => 50, 'currency' => 'Broken']);
        $this->assertFalse($money->isValid());
        $obj = $money->evaluate();
    }

    /**
     * @covers ::evaluate
     */
    public function testStringAmount()
    {
        $money = new Money();
        $money->setValue(['amount' => '50', 'currency' => 'XTS']);
        $this->assertTrue($money->isValid());
        $obj = $money->evaluate();
        $this->assertInstanceOf(BaseMoney::class, $obj);
        $this->assertSame('50', $obj->getAmount());
        $this->assertEquals(
            new Currency('XTS'),
            $obj->getCurrency()
        );
    }
}
