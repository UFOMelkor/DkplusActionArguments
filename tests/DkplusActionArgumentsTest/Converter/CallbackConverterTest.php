<?php
namespace DkplusActionArgumentsTest\Converter;

use DkplusActionArguments\Converter\CallbackConverter;
use PHPUnit_Framework_TestCase as TestCase;

class CallbackConverterTest extends TestCase
{
    public function testShouldConvertAValueToAnEntity()
    {
        $value   = 'foo';
        $entity  = $this->getMock('stdClass');
        $service = $this->getMockBuilder('stdClass')
                        ->setMethods(array('findOneByName'))
                        ->getMock();
        $service->expects($this->once())
                ->method('findOneByName')
                ->with($value)
                ->will($this->returnValue($entity));

        $converter = new CallbackConverter(array($service, 'findOneByName'));
        $this->assertSame($entity, $converter->convert($value));
    }
}
 