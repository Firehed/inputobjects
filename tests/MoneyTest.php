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

    use InputObjectTestTrait;

    protected function getInputObject()
    {
        return new Money();
    }

    public function evaluations()
    {
        return [
            [['amount' => 0, 'currency' => 'XTS'], BaseMoney::XTS(0)],
            [['amount' => '0', 'currency' => 'XTS'], BaseMoney::XTS(0)],
            [['amount' => 50, 'currency' => 'XTS'], BaseMoney::XTS(50)],
            [['amount' => '50', 'currency' => 'XTS'], BaseMoney::XTS(50)],
            [['amount' => -50, 'currency' => 'XTS'], BaseMoney::XTS(-50)],
        ];
    }

    public function invalidEvaluations()
    {
        return [
            ['amount' => '', 'currency' => 'XTS'],
            ['amount' => 5.5, 'currency' => 'XTS'],
            ['amount' => '0lol', 'currency' => 'XTS'],
            ['amount' => 50, 'currency' => 'Broken'],
        ];
    }
}
