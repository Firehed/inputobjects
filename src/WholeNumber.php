<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

/**
 * This is being retained for backwards compatibiltiy only, but is deprecated.
 */
class WholeNumber extends Integer
{
    public function __construct()
    {
        parent::__construct();
        trigger_error(
            'Moved to Firehed\InputObjects\Integer',
            \E_USER_DEPRECATED
        );
    }
}
