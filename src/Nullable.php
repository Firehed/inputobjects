<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Nullable extends InputObject
{
    /** @var InputObject */
    private $type;

    public function __construct(InputObject $type)
    {
        parent::__construct();
        $this->type = $type;
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): bool
    {
        if ($value === null) {
            return true;
        }
        return $this->type->setValue($value)->isValid();
    }

    public function evaluate()
    {
        $value = parent::evaluate();
        if ($value === null) {
            return $value;
        }
        return $this->type->setValue($value)->evaluate();
    }
}
