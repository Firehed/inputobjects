<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls as OCC;
use UnexpectedValueException;

/**
 * @covers Firehed\InputObjects\ListOf
 */
class ListOfTest extends \PHPUnit\Framework\TestCase
{

    public function testConstruct(): void
    {
        $io = $this->getMockForAbstractClass(InputObject::class);
        self::assertInstanceOf(
            ListOf::class,
            new ListOf($io),
            'Construct failed'
        );
    }

    /**
     * @dataProvider values
     * @param string[] $input
     * @param bool[] $mock_returns
     */
    public function testValidate(array $input, array $mock_returns, bool $is_valid): void
    {
        // Generally, assert that each of the input values is validated against
        // the input type provided in the constructor
        $io = $this->getMockForAbstractClass(
            InputObject::class,
            ['validate']
        );
        $io->expects(self::atLeastOnce())
            ->method('validate')
            // ->will($this->onConsectiveCalls(...$mock_returns));
            ->will(new OCC($mock_returns));

        $list_of = new ListOf($io);
        $list_of->setValue($input);

        if ($is_valid) {
            self::assertTrue($list_of->isValid(), 'Value should be valid');
        } else {
            self::assertFalse($list_of->isValid(), 'Value should be invalid');
        }
    }

    /**
     * @dataProvider values
     * @param string[] $input
     * @param bool[] $mock_returns
     */
    public function testEvaluate(array $input, array $mock_returns, bool $is_valid): void
    {
        $io = $this->createMock(InputObject::class);
        $map = [];
        $out_map = [];
        foreach ($input as $i => $value) {
            $map[$i] = [$value, $mock_returns[$i]];
            $out_map[$i] = new \StdClass();
        }

        $io->expects(self::any())
            ->method('validate')
            ->will(self::returnValueMap($map));

        $list_of = new ListOf($io);
        $list_of->setValue($input);
        if ($is_valid) {
            $io->expects(self::atLeastOnce())
                ->method('evaluate')
                ->will(new OCC($out_map));
            self::assertSame(
                $out_map,
                $list_of->evaluate(),
                'The evaluated list did not return the child evaluate() values'
            );
        } else {
            $this->expectException(UnexpectedValueException::class);
            $list_of->evaluate();
        }
    }

    /**
     * @dataProvider nonLists
     * @param mixed $non_list
     */
    public function testNonListsAreRejected($non_list): void
    {
        $io = $this->getMockForAbstractClass(InputObject::class);
        $io->expects(self::never())
            ->method('validate');
        $list_of = new ListOf($io);
        $list_of->setValue($non_list);
        self::assertFalse($list_of->isValid());
    }

    public function testSetSeapratorReturnsThis(): void
    {
        $io = $this->createMock(InputObject::class);
        $listOf = new ListOf($io);
        self::assertSame($listOf, $listOf->setSeparator(','));
    }

    /**
     * @dataProvider separatorValues
     * @param mixed[] $output
     */
    public function testStringValuesAreAcceptedWithSeparator(
        InputObject $io,
        string $separator,
        string $input,
        array $output
    ): void {
        $listOf = new ListOf($io);
        $listOf->setSeparator($separator);

        $listOf->setValue($input);
        self::assertSame($output, $listOf->evaluate());
    }

    /**
     * @return array{InputObject, string, string, mixed[]}[]
     */
    public function separatorValues(): array
    {
        $text = new Text();
        $number = new Number();
        $listOfText = (new ListOf($text))->setSeparator('|');
        return [
            [$text, '#', '', []],
            [$text, '#', 'foo', ['foo']],
            [$text, '#', 'foo#bar', ['foo', 'bar']],
            [$text, '#', 'foo#bar#baz', ['foo', 'bar', 'baz']],
            [$text, ',', '', []],
            [$text, ',', 'foo', ['foo']],
            [$text, ',', 'foo,bar', ['foo', 'bar']],
            [$text, ',', 'foo,bar,baz', ['foo', 'bar', 'baz']],
            [$text, '|', '', []],
            [$text, '|', 'foo', ['foo']],
            [$text, '|', 'foo|bar', ['foo', 'bar']],
            [$text, '|', 'foo|bar|baz', ['foo', 'bar', 'baz']],
            [$number, ',', '1,2,3', [1, 2, 3]],
            [$number, ',', '1,2.3', [1, 2.3]],
            // This should recursively decode
            [$listOfText, ',', 'a,b,c|d|e,f,g|h,,|', [
                ['a'],
                ['b'],
                ['c', 'd', 'e'],
                ['f'],
                ['g', 'h'],
                [],
                ['',''],
            ]],
        ];
    }

    /**
     * @return array{string[], bool[], bool}[]
     */
    public function values()
    {
        return [
            [['value'], [true], true],
            [['value'], [false], false],

            [['v1', 'v2'], [true, true], true],
            [['v1', 'v2'], [false, false], false],
            [['v1', 'v2'], [true, false], false],
            [['v1', 'v2'], [false, true], false],
        ];
    }

    /**
     * @return array{mixed}[]
     */
    public function nonLists()
    {
        return [
            [1],
            ['string'],
            [false],
            [null],
            [['key' => 'value']], // dict, not list
        ];
    }
}
