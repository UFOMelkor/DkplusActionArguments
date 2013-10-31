<?php
namespace DkplusActionArgumentsTest;

use DkplusActionArguments\Configuration\ArgumentServiceFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Configuration\ArgumentServiceFactory
 */
class ArgumentServiceFactoryTest extends TestCase
{
    public function testShouldCreateAnArgumentFactory()
    {
        $converterFactory = $this->getMockBuilder('DkplusActionArguments\\Converter\\ConverterFactory')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('DkplusActionArguments\\Converter\\ConverterFactory')
                 ->will($this->returnValue($converterFactory));

        $factory = new ArgumentServiceFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Configuration\\ArgumentFactory',
            $factory->createService($services)
        );
    }
}
