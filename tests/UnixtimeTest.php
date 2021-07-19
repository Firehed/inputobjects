<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

use DateTimeImmutable;

/**
 * @coversDefaultClass Firehed\InputObjects\Unixtime
 * @covers ::<protected>
 * @covers ::<private>
 */
class UnixtimeTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    public function getInputObject(): InputObject
    {
        return new Unixtime();
    }

    public function evaluations(): array
    {
        return [
            // This does _not_ test float inputs as native types since it is
            // not recommended to provide them that way in order to avoid
            // precision issues.
            ['1500000000.999999', new DateTimeImmutable('2017-07-14 02:40:00.999999 UTC')],
            ['1500000000.999', new DateTimeImmutable('2017-07-14 02:40:00.999000 UTC')],
            ['1500000000', new DateTimeImmutable('2017-07-14 02:40:00.000000 UTC')],
            [1500000000, new DateTimeImmutable('2017-07-14 02:40:00.000000 UTC')],
            [0, new DateTimeImmutable('1970-01-01 00:00:00.000000 UTC')],
            [-1500000000, new DateTimeImmutable('1922-06-20 21:20:00.000000 UTC')],
            ['-1500000000', new DateTimeImmutable('1922-06-20 21:20:00.000000 UTC')],
            ['-1500000000.999', new DateTimeImmutable('1922-06-20 21:20:00.999000 UTC')],
            ['-1500000000.999999', new DateTimeImmutable('1922-06-20 21:20:00.999999 UTC')],
        ];
    }

    public function invalidEvaluations(): array
    {
        return [
            [null],
            [true],
            [false],
            [[]],
            [''],
            ['fifty'],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getDefaultValue
     */
    public function testConstructWithNothing()
    {
        $ut = new Unixtime();
        $this->assertNull($ut->getDefaultValue());
    }

    /**
     * @covers ::__construct
     * @covers ::getDefaultValue
     */
    public function testConstructWithFalse()
    {
        $ut = new Unixtime(false);
        $this->assertNull($ut->getDefaultValue());
    }

    /**
     * @covers ::__construct
     * @covers ::getDefaultValue
     */
    public function testConstructWithTrue()
    {
        $ut = new Unixtime(true);
        $dt = $ut->getDefaultValue();
        $this->assertInstanceOf(DateTimeImmutable::class, $dt);
        $diff = $dt->diff(new DateTimeImmutable());
        // On an absurdly slow system this test could fail, so this gives it
        // a little flexibility
        if (version_compare(PHP_VERSION, '7.1.0', '>=')) {
            $this->assertTrue(($diff->s + $diff->f) < 1, 'Should have been < 1sec from now');
        } else {
            $this->assertTrue($diff->s < 2, 'Should have been one second or less difference');
        }
        $this->assertSame(0, $diff->i, 'Minutes != 0');
        $this->assertSame(0, $diff->h, 'Hours != 0');
        $this->assertSame(0, $diff->d, 'Days != 0');
        $this->assertSame(0, $diff->m, 'Months != 0');
        $this->assertSame(0, $diff->y, 'Years != 0');
    }
}
