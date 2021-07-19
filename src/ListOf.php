<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use InvalidArgumentException;

/**
 * The ListOf InputObject is used in situations where we expect to receive
 * an array of some other input type. Unlike most other InputObjects, it
 * requires explicit configuration via
 *
 * @{method:setType@Firehed.Input.Objects\InputObject}. Conceptually, it applies
 * @{function@libphutil:assert_instances_of}            to each item in the provided
 * array.
 */
class ListOf extends InputObject
{
    /**
     * @var ?non-empty-string
     */
    private ?string $separator = null;

    private InputObject $type;

    public function __construct(InputObject $type)
    {
        parent::__construct();
        $this->type = $type;
    }

    public function setSeparator(string $separator): self
    {
        if (strlen($separator) === 0) {
            throw new InvalidArgumentException('Separator must not be empty');
        }
        $this->separator = $separator;
        return $this;
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): bool
    {
        if (is_string($value) && $this->separator !== null) {
            // explode(any, '') returns `['']` not `[]`; force an override
            if ($value === '') {
                $value = [];
            } else {
                $value = explode($this->separator, $value);
            }
            $this->setValue($value);
        }
        if (!is_array($value)) {
            return false;
        }
        // Todo: support min/max on count?

        foreach ($value as $key => $item) {
            // A dictionary was passed, no good
            if (!is_int($key)) {
                return false;
            }
            if (!$this->type->setValue($item)->isValid()) {
                return false;
            }
        }
        return true;
    }

    public function evaluate()
    {
        $values = parent::evaluate();
        foreach ($values as $key => $value) {
            $values[$key] = $this->type->setValue($value)->evaluate();
        }
        return $values;
    }
}
