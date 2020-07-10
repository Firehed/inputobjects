<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use DateTime;
use DateTimeImmutable;
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
        if (is_numeric($value)) {
            $value = '@' . $value;
        }

        if ($this->returnMutable) {
            return new DateTime($value);
        } else {
            return new DateTimeImmutable($value);
        }
    }
}
