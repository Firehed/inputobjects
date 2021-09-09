<?php

namespace Firehed\InputObjects;

use Firehed\Input\Objects\InputObject;
use Firehed\Input\Exceptions\InputException;

/**
 * @covers Firehed\InputObjects\Structure
 * */
class StructureTest extends \PHPUnit\Framework\TestCase
{

    public function testExecuteValidData(): void
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects(self::atLeastOnce())
            ->method('getRequiredInputs')
            ->will(self::returnValue(['string' => new Text()]));
        $structure->expects(self::atLeastOnce())
            ->method('getOptionalInputs')
            ->will(self::returnValue([]));
        $ret = $structure->setValue(['string' => 'this is a string'])->evaluate();
        self::assertSame(
            ['string' => 'this is a string'],
            $ret,
            'Execute should have returned an array'
        );
    }

    public function testExecuteInvalidData(): void
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects(self::atLeastOnce())
            ->method('getRequiredInputs')
            ->will(self::returnValue(['string' => new Text()]));
        $structure->expects(self::atLeastOnce())
            ->method('getOptionalInputs')
            ->will(self::returnValue([]));
        $this->expectException(InputException::class);
        $this->expectExceptionCode(InputException::INVALID_VALUES);
        $structure->setValue(['string' => 123])->evaluate();
    }

    public function testExecuteWithVariousInputErrors(): void
    {
        $required = [
            'int' => new Integer(),
            'str' => new Text(),
        ];

        $input = [
            'int' => 'three',
            'new' => 'some new value',
        ];

        $structure = $this->makeStructure($required, []);
        $structure->setValue($input);
        try {
            $structure->evaluate();
            self::fail('An InputException should have been thrown');
        } catch (InputException $e) {
            self::assertSame(['int'], $e->getInvalid());
            self::assertSame(['str'], $e->getMissing());
            self::assertSame(['new'], $e->getUnexpected());
        }
    }

    public function testRecursiveStructureSuccessCase(): void
    {
        $innerReq = [
            'amount' => new Integer(),
            'currency' => new Text(),
        ];
        $outerReq = [
            'price' => $this->makeStructure($innerReq, []),
            'name' => new Text(),
        ];
        $input = [
            'price' => [
                'amount' => 100,
                'currency' => 'XTS',
            ],
            'name' => 'Widget',
        ];
        $structure = $this->makeStructure($outerReq, []);
        $ret = $structure->setValue($input)->evaluate();
        self::assertSame($input, $ret, 'Output of evaluate was wrong');
    }

    public function testRecursiveStructureFailureCase(): void
    {
        $innerReq = [
            'amount' => new Integer(),
            'currency' => new Text(),
        ];
        $outerReq = [
            'price' => $this->makeStructure($innerReq, []),
            'name' => new Text(),
        ];
        $input = [
            'price' => [
                'amount' => '$100',
            ],
            'title' => 'Widget',
        ];
        $structure = $this->makeStructure($outerReq, []);
        try {
            $ret = $structure->setValue($input)->evaluate();
            self::fail('An InputException should have been thrown');
        } catch (InputException $e) {
            self::assertSame(['price.amount'], $e->getInvalid(), 'Invalid wrong');
            self::assertSame(['price.currency', 'name'], $e->getMissing(), 'Missing wrong');
            self::assertSame(['title'], $e->getUnexpected(), 'Unexpected wrong');
        }
    }


    /**
     * @dataProvider nonArrays
     * @param mixed $input
     */
    public function testNonArrayInput($input): void
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects(self::any())
            ->method('getRequiredInputs')
            ->will(self::returnValue([]));
        $structure->expects(self::any())
            ->method('getOptionalInputs')
            ->will(self::returnValue([]));
        $structure->setValue($input);
        self::assertFalse(
            $structure->isValid(),
            'Input should not be valid'
        );
    }

    // -(  DataProviders  )-----------------------------------------------------

    /**
     * @return array{mixed}[]
     */
    public function nonArrays()
    {
        return [
            [null],
            [true],
            [false],
            [0],
            [1.2],
            ["[]"],
        ];
    }

    /**
     * @param array<string, InputObject> $required
     * @param array<string, InputObject> $optional
     */
    private function makeStructure(array $required, array $optional): Structure
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects(self::atLeastOnce())
            ->method('getRequiredInputs')
            ->will(self::returnValue($required));
        $structure->expects(self::atLeastOnce())
            ->method('getOptionalInputs')
            ->will(self::returnValue($optional));
        return $structure;
    }
}
