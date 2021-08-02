<?php

declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * @coversDefaultClass Firehed\InputObjects\DateTimeString
 * @covers ::<protected>
 * @covers ::<private>
 */
class DateTimeStringTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    public function getInputObject(): InputObject
    {
        return new DateTimeString();
    }

    /**
     * @return array{string, DateTimeInterface}[]
     */
    public function evaluations(): array
    {
        $cases = array_map(function ($string): array {
            return [$string, new DateTimeImmutable($string)];
        }, [
            '2018-05-09T22:55:30+00:00',
            '2018-05-09T22:55:30+0000',
        ]);
        return $cases;
    }

    /**
     * @return array{mixed}
     */
    public function invalidEvaluations(): array
    {
        return [
            // By default, unixtime should be blocked
            [1525932105],
            ['1525932105'],
            // PHP internals
            ['@1525932105'],
            ['+1 week'],
            ['last monday'],
            ['2018-05-09 10:55:30PM'],
            ['5/9/2018 10:55:30PM'],
            ['5/9/18 10:55:30PM'],
            ['05/09/2018 10:55:30PM'],
            ['05/09/18 10:55:30PM'],
            // Just garbage
            ['not a date'],
            [true],
            [['a']],
            [['a' => 'b']],
        ];
    }

    /**
     * @covers ::setReturnMutable
     * @covers ::evaluate
     */
    public function testSetReturnMutable(): void
    {
        $dt = $this->getInputObject();
        assert($dt instanceof DateTimeString);
        self::assertSame(
            $dt,
            $dt->setReturnMutable(true),
            'setReturnMutable should return $this'
        );

        $ret = $dt->setValue('2018-05-09T22:55:30+0000')->evaluate();
        self::assertInstanceOf(DateTimeInterface::class, $ret);
        self::assertInstanceOf(DateTime::class, $ret);
        self::assertNotInstanceOf(DateTimeImmutable::class, $ret);
    }

    /**
     * @covers ::setAllowUnixtime
     * @covers ::validate
     * @covers ::evaluate
     */
    public function testSetAllowUnixtime(): void
    {
        $dt = $this->getInputObject();
        assert($dt instanceof DateTimeString);
        self::assertSame(
            $dt,
            $dt->setAllowUnixtime(true),
            'setAllowUnixtime should return $this'
        );
        $target = new DateTimeImmutable('@1525932105');

        $retStr = $dt->setValue('1525932105')->evaluate();
        self::assertEquals($target, $retStr, 'Unixtime string did not evaluate correctly');

        $retInt = $dt->setValue(1525932105)->evaluate();
        self::assertEquals($target, $retInt, 'Unixtime int did not evaluate correctly');
    }

    public function fractionalUnixtimeProvider()
    {
        return [
            // Mutable, as string
            ['1594344367.000001', true, new DateTime('2020-07-10T01:26:07.000001+00:00')],
            ['1594344367.001', true, new DateTime('2020-07-10T01:26:07.001+00:00')],
            ['1594344367.126', true, new DateTime('2020-07-10T01:26:07.126+00:00')],
            ['1594344367.999', true, new DateTime('2020-07-10T01:26:07.999+00:00')],
            ['1594344367.999999', true, new DateTime('2020-07-10T01:26:07.999999+00:00')],
            // Mutable, as float
            [1594344367.000001, true, new DateTime('2020-07-10T01:26:07.000001+00:00')],
            [1594344367.001, true, new DateTime('2020-07-10T01:26:07.001+00:00')],
            [1594344367.126, true, new DateTime('2020-07-10T01:26:07.126+00:00')],
            [1594344367.999, true, new DateTime('2020-07-10T01:26:07.999+00:00')],
            [1594344367.999999, true, new DateTime('2020-07-10T01:26:07.999999+00:00')],
            // Simple test to make sure immutable version works. Same code
            // path.
            ['1594344367.126', false, new DateTimeImmutable('2020-07-10T01:26:07.126+00:00')],
        ];
    }

    /**
     * @param mixed $value The input value under test
     * @param bool $returnMutable The value to pass to setReturnMutabke
     *
     * @covers ::evaluate
     * @dataProvider fractionalUnixtimeProvider
     */
    public function testFractionalUnixtimeBug34($value, $returnMutable, DateTimeInterface $expected)
    {
        $dt = $this->getInputObject();
        $dt->setAllowUnixtime(true);
        $dt->setReturnMutable($returnMutable);

        $ret = $dt->setValue($value)->evaluate();
        $this->assertInstanceOf(DateTimeInterface::class, $ret);

        $this->assertEquals($expected, $ret, 'Unixtime string with fraction did not evaluate correctly');
    }

    /** @covers ::__construct */
    public function testCustomFormats(): void
    {
        $input = '2018-05-09 10:55:30PM';

        $dt = new DateTimeString(['Y-m-d g:i:sA']);
        $ret = $dt->setValue($input)->evaluate();

        $target = new DateTimeImmutable($input);
        self::assertEquals($target, $ret, 'Custom format did not evaluate');

        // This is a default-valid format
        $invalid = '2018-05-09T22:55:30+00:00';
        self::assertFalse($dt->setValue($invalid)->isValid());
    }
}
