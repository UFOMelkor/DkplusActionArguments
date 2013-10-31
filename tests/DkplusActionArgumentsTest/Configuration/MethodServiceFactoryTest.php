<?php
namespace DkplusActionArgumentsTest\Configuration;

use DkplusActionArguments\Configuration\MethodServiceFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Configuration\MethodServiceFactory
 */
class MethodServiceFactoryTest extends TestCase
{
    public function testShouldCreateAMethodFactory()
    {
        $argumentFactory = $this->getMockBuilder('DkplusActionArguments\\Configuration\\ArgumentFactory')
                                ->disableOriginalConstructor()
                                ->getMock();

        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('DkplusActionArguments\\Configuration\\ArgumentFactory')
                 ->will($this->returnValue($argumentFactory));

        $factory = new MethodServiceFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Configuration\\MethodFactory',
            $factory->createService($services)
        );
    }
}
