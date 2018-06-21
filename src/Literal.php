<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

class Literal extends InputObject
{
    private $matchTarget;

    public function __construct($value)
    {
        parent::__construct();
        // Should validate this only has scalars or array containing only
        // scalars (recusrively) - since === will always fail on actual objects
        // due to ref check
        $this->matchTarget = $value;
    }

    protected function validate($value): bool
    {
        return $this->matchTarget === $value;
    }
}
