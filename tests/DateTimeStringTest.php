<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use DateTime;
use DateTimeImmutable;
/**
 * @coversDefaultClass Firehed\InputObjects\DateTimeString
 * @covers ::<protected>
 * @covers ::<private>
 */
class DateTimeStringTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    public function getInputObject()
    {
        return new DateTimeString();
    }

    public function evaluations()
    {
        return array_map(function ($string) {
            return [$string, new DateTimeImmutable($string)];
        }, [
            '2018-05-09T22:55:30+00:00',
            '2018-05-09T22:55:30+0000',
            '2018-05-09 10:55:30PM',
            '5/9/2018 10:55:30PM',
            '5/9/18 10:55:30PM',
            '05/09/2018 10:55:30PM',
            '05/09/18 10:55:30PM',
        ]);
    }

    public function invalidEvaluations()
    {
        return [
            // PHP internals
            ['@1525932105'],
            ['+1 week'],
            ['last monday'],
            // Just garbage
            ['not a date'],
            [true],
            [[]],
        ];
    }
}
