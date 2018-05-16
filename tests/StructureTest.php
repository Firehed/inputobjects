<?php

namespace Firehed\InputObjects;

use Firehed\Input\Exceptions\InputException;

/**
 * @coversDefaultClass Firehed\InputObjects\Structure
 * @covers ::<protected>
 * @covers ::<private>
 * */
class StructureTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @covers ::evaluate
     */
    public function testExecuteValidData()
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects($this->atLeastOnce())
            ->method('getRequiredInputs')
            ->will($this->returnValue(['string' => new Text()]));
        $structure->expects($this->atLeastOnce())
            ->method('getOptionalInputs')
            ->will($this->returnValue([]));
        $ret = $structure->setValue(['string' => 'this is a string'])->evaluate();
        $this->assertSame(
            ['string' => 'this is a string'],
            $ret,
            'Execute should have returned an array'
        );
    }

    /**
     * @covers ::evaluate
     */
    public function testExecuteInvalidData()
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects($this->atLeastOnce())
            ->method('getRequiredInputs')
            ->will($this->returnValue(['string' => new Text()]));
        $structure->expects($this->atLeastOnce())
            ->method('getOptionalInputs')
            ->will($this->returnValue([]));
        $this->expectException(InputException::class);
        $this->expectExceptionCode(InputException::INVALID_VALUES);
        $structure->setValue(['string' => 123])->evaluate();
    } // testExecuteInvalidData

    /**
     * @covers ::evaluate
     */
    public function testExecuteWithVariousInputErrors()
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
            $this->fail('An InputException should have been thrown');
        } catch (InputException $e) {
            $this->assertSame(['int'], $e->getInvalid());
            $this->assertSame(['str'], $e->getMissing());
            $this->assertSame(['new'], $e->getUnexpected());
        }
    }

    public function testRecursiveStructureSuccessCase()
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
        $this->assertSame($input, $ret, 'Output of evaluate was wrong');
    }

    public function testRecursiveStructureFailureCase()
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
            $this->fail('An InputException should have been thrown');
        } catch (InputException $e) {
            $this->assertSame(['price.amount'], $e->getInvalid(), 'Invalid wrong');
            $this->assertSame(['price.currency', 'name'], $e->getMissing(), 'Missing wrong');
            $this->assertSame(['title'], $e->getUnexpected(), 'Unexpected wrong');
        }
    }


    /**
     * @dataProvider nonArrays
     * */
    public function testNonArrayInput($input)
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects($this->any())
            ->method('getRequiredInputs')
            ->will($this->returnValue([]));
        $structure->expects($this->any())
            ->method('getOptionalInputs')
            ->will($this->returnValue([]));
        $structure->setValue($input);
        $this->assertFalse(
            $structure->isValid(),
            'Input should not be valid'
        );
    } // testNonArrayInput

    // -(  DataProviders  )-----------------------------------------------------

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
    } // nonArrays

    private function makeStructure(array $required, array $optional): Structure
    {
        $structure = $this->getMockForAbstractClass(Structure::class);
        $structure->expects($this->atLeastOnce())
            ->method('getRequiredInputs')
            ->will($this->returnValue($required));
        $structure->expects($this->atLeastOnce())
            ->method('getOptionalInputs')
            ->will($this->returnValue($optional));
        return $structure;
    }
}
