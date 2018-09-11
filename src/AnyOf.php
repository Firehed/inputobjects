<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Exceptions\InputException;
use Firehed\Input\Objects\InputObject;
use LogicException;

class AnyOf extends InputObject
{
    /** @var InputObject[] */
    private $types = [];

    public function __construct(InputObject ...$types)
    {
        parent::__construct();
        $this->types = $types;
    }

    protected function validate($value): bool
    {
        foreach ($this->types as $type) {
            try {
                if ($type->setValue($value)->isValid()) {
                    return true;
                }
            } catch (InputException $e) {
            }
        }
        return false;
    }

    public function evaluate()
    {
        $value = $this->getValue();
        foreach ($this->types as $type) {
            try {
                if ($type->setValue($value)->isValid()) {
                    return $type->evaluate();
                }
            } catch (InputException $e) {
            }
        }
        throw new LogicException('No valid value was found during evaluate despite validate passing. Please file a bug with a reproduce case!');
    }
}
