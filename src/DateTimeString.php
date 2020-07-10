<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use DateTime;
use DateTimeImmutable;
use DateInterval;
use Firehed\Input\Objects\InputObject;

/**
 * Validator for various string representations of a DateTime object.
 *
 * By default, it accepts only a small number of rigid (but standardized)
 * formats in order to reduce internationalization-related ambiguities that you
 * can get from e.g. `strtotime`, plus some extremely-liberal formats such as
 * relative times. Different formats can be provided in the constructor.
 *
 * Further, it will reject unix timestamps unless specifically allowed, and
 * return a `DateTimeImmutable` over a `DateTime` (both of these implement
 * `DateTimeInterface` which is a more versatile typehint, but reality wins).
 * Both of these behaviors can be altered via `setAllowUnixtime()` and
 * `setReturnMutable()` respectively.
 */
class DateTimeString extends InputObject
{
    const STANDARD_FORMATS = [
        DateTime::ATOM,
        DateTime::ISO8601,
    ];

    /** @var string[] */
    private $validFormats;

    /** @var bool */
    private $allowUnixtime = false;

    /** @var bool */
    private $returnMutable = false;

    /**
     * @param string[] $validFormats
     */
    public function __construct(array $validFormats = [])
    {
        parent::__construct();
        if (!$validFormats) {
            $validFormats = self::STANDARD_FORMATS;
        }
        $this->validFormats = $validFormats;
    }

    public function setAllowUnixtime(bool $allowed): DateTimeString
    {
        $this->allowUnixtime = $allowed;
        return $this;
    }

    public function setReturnMutable(bool $mutable): DateTimeString
    {
        $this->returnMutable = $mutable;
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function validate($value): bool
    {
        if ($this->allowUnixtime) {
            if (is_numeric($value)) {
                return true;
            }
        }

        if (!is_string($value)) {
            return false;
        }

        foreach ($this->validFormats as $format) {
            if (DateTime::createFromFormat($format, $value)) {
                return true;
            }
        }

        return false;
    }

    public function evaluate()
    {
        $value = $this->getValue();
        $offset = null;
        if (is_numeric($value)) {
            $seconds = (int)$value;
            // Intentionally loose equality here: looking for fractional part
            if ($seconds != $value) {
                $parts = explode('.', (string)$value, 2);
                assert(count($parts) === 2);
                // Yes, this is quite clumsy. It's also the most
                // straightforward way to get the fraction part while avoiding
                // (most?) floating point errors, which fmod may introduce.
                $fraction = (float)('0.' . $parts[1]);

                $offset = new DateInterval('PT0S');
                $offset->f = $fraction;
            }

            $value = '@' . $seconds;
        }

        if ($this->returnMutable) {
            $response = new DateTime($value);
        } else {
            $response = new DateTimeImmutable($value);
        }
        if ($offset) {
            $response = $response->add($offset);
        }
        return $response;
    }
}
