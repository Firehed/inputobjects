<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Exceptions\InputException;
use Firehed\Input\Objects\InputObject;

class URL extends InputObject
{
    private const COMPONENT_MAP = [
        PHP_URL_SCHEME => 'scheme',
        PHP_URL_HOST => 'host',
        PHP_URL_PORT => 'port',
        PHP_URL_USER => 'user',
        PHP_URL_PASS => 'pass',
        PHP_URL_PATH => 'path',
        PHP_URL_QUERY => 'query',
        PHP_URL_FRAGMENT => 'fragment',
    ];

    /** @var int[] A list of required PHP_URL_ constants */
    private $requiredComponents;

    /**
     * @param int[] $requiredComponents PHP_URL_ constants
     */
    public function __construct(array $requiredComponents = [])
    {
        parent::__construct();
        $this->requiredComponents = array_map(function ($const) {
            return self::COMPONENT_MAP[$const];
        }, $requiredComponents);
    }

    protected function validate($value): bool
    {
        $parts = parse_url($value);
        if ($parts === false) {
            return false;
        }
        $missing = [];
        foreach ($this->requiredComponents as $component) {
            if (!array_key_exists($component, $parts)) {
                $missing[] = $component;
            }
        }
        if ($missing) {
            throw new InputException(InputException::MISSING_VALUES, $missing);
        }
        return true;
    }
}
