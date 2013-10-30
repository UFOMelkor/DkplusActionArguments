<?php
namespace DkplusActionArgumentsTest\Converter;

use DkplusActionArguments\Converter\DoctrineConverter;
use PHPUnit_Framework_TestCase as TestCase;

class DoctrineConverterTest extends TestCase
{
    public function testShouldConvertAValueToAnEntity()
    {
        $value  = 5;
        $entity = $this->getMock('stdClass');

        $repository = $this->getMockForAbstractClass('Doctrine\\Common\\Persistence\\ObjectRepository');
        $repository->expects($this->once())
                   ->method('find')
                   ->with($value)
                   ->will($this->returnValue($entity));

        $converter = new DoctrineConverter($repository, 'find');
        $this->assertSame($entity, $converter->convert($value));
    }
}
 