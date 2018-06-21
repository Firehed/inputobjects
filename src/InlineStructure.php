<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

class InlineStructure extends Structure
{
    private $required;
    private $optional;

    public function __construct(array $required, array $optional = [])
    {
        parent::__construct();
        $this->required = $required;
        $this->optional = $optional;
    }

    public function getRequiredInputs(): array
    {
        return $this->required;
    }

    public function getOptionalInputs(): array
    {
        return $this->optional;
    }
}
