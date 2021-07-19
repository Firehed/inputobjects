<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

class InlineStructure extends Structure
{
    /**
     * @var \Firehed\Input\Objects\InputObject[]
     */
    private $required;

    /**
     * @var \Firehed\Input\Objects\InputObject[]
     */
    private $optional;

    /**
     * @param \Firehed\Input\Objects\InputObject[] $required
     * @param \Firehed\Input\Objects\InputObject[] $optional
     */
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
