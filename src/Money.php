<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use SebastianBergmann\Money as M;

/**
 * Define a common Money input structure that, when evaluated, returns a Money
 * object.
 *
 * Expects a dictionary containing two fields:
 * - amount: an integer in the base unit of currency ($1.50 would be 150)
 * - currency: the 3-character ISO code of the currency
 *
 */
class Money extends Structure
{

    public function getRequiredInputs(): array
    {
        return [
            'amount' => new WholeNumber(),
            'currency' => (new Text())->setMin(3)->setMax(3),
        ];
    }

    public function getOptionalInputs(): array
    {
        return [];
    }

    public function evaluate(): M\Money
    {
        $ret = parent::evaluate();
        return new M\Money($ret['amount'], new M\Currency($ret['currency']));
    }

}
