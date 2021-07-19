<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use Money\Currency;
use Money\Money as BaseMoney;

/**
 * @coversDefaultClass Firehed\InputObjects\Money
 * @covers ::<protected>
 * @covers ::<private>
 * @covers ::getRequiredInputs
 * @covers ::getOptionalInputs
 */
class MoneyTest extends \PHPUnit\Framework\TestCase
{

    use InputObjectTestTrait;

    protected function getInputObject(): InputObject
    {
        return new Money();
    }

    /**
     * @return array{array{amount: mixed, currency: string}, BaseMoney}[]
     */
    public function evaluations(): array
    {
        return [
            [['amount' => 0, 'currency' => 'XTS'], BaseMoney::XTS(0)],
            [['amount' => '0', 'currency' => 'XTS'], BaseMoney::XTS(0)],
            [['amount' => 50, 'currency' => 'XTS'], BaseMoney::XTS(50)],
            [['amount' => '50', 'currency' => 'XTS'], BaseMoney::XTS(50)],
            [['amount' => -50, 'currency' => 'XTS'], BaseMoney::XTS(-50)],
        ];
    }

    /**
     * @return array{amount: mixed, currency: mixed}[]
     */
    public function invalidEvaluations(): array
    {
        return [
            ['amount' => '', 'currency' => 'XTS'],
            ['amount' => 5.5, 'currency' => 'XTS'],
            ['amount' => '0lol', 'currency' => 'XTS'],
            ['amount' => 50, 'currency' => 'Broken'],
        ];
    }
}
