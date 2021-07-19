<?php
declare(strict_types=1);

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;

/**
 * @coversDefaultClass Firehed\InputObjects\Uuid
 * @covers ::<protected>
 * @covers ::<private>
 */
class UuidTest extends \PHPUnit\Framework\TestCase
{
    use InputObjectTestTrait;

    public function getInputObject(): InputObject
    {
        return new Uuid();
    }

    /**
     * @return array{string, string}[]
     */
    public function evaluations(): array
    {
        return [
            // nil
            ['00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000'],
            // v1
            ['585fa6e2-95b7-11e8-9eb6-529269fb1459', '585fa6e2-95b7-11e8-9eb6-529269fb1459'],
            ['585FA6E2-95B7-11E8-9EB6-529269FB1459', '585fa6e2-95b7-11e8-9eb6-529269fb1459'],
            // v3
            ['11a38b9a-b3da-360f-9353-a5a725514269', '11a38b9a-b3da-360f-9353-a5a725514269'],
            ['11A38B9A-B3DA-360F-9353-A5A725514269', '11a38b9a-b3da-360f-9353-a5a725514269'],
            // v4
            ['0826dacd-36ca-4172-9cc7-9be623a829d0', '0826dacd-36ca-4172-9cc7-9be623a829d0'],
            ['0826DACD-36CA-4172-9CC7-9BE623A829D0', '0826dacd-36ca-4172-9cc7-9be623a829d0'],
            // v5
            ['c4a760a8-dbcf-5254-a0d9-6a4474bd1b62', 'c4a760a8-dbcf-5254-a0d9-6a4474bd1b62'],
            ['C4A760A8-DBCF-5254-A0D9-6A4474BD1B62', 'c4a760a8-dbcf-5254-a0d9-6a4474bd1b62'],
        ];
    }

    /**
     * @return array{mixed}[]
     */
    public function invalidEvaluations(): array
    {
        return [
            [null],
            [true],
            [false],
            [12345],
            [12345.6789],
            [[]],
            [['a' => 'b']],
            ['some string'],
            ['string containing a c6f96f06-8ecc-4ceb-bb2a-e20faf170b0b uuid'],
            ['c6f96f06-8ecc-4ceb-bb2a-e20faf170b0b uuid at start'],
            ['uuid at end c6f96f06-8ecc-4ceb-bb2a-e20faf170b0b'],
            // Text format is correct but these are invalid
            ['00000000-0000-0000-0000-000000000001'],
            ['00000000-0000-6000-0000-000000000000'],
            ['00000000-0000-7000-0000-000000000000'],
            ['00000000-0000-8000-0000-000000000000'],
            ['00000000-0000-9000-0000-000000000000'],
            // v2 is intentionally excluded
            ['00000000-0000-2000-0000-000000000000'],
        ];
    }
}
