<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\ZfcRbacServiceDecoratorFactory;
use PHPUnit_Framework_TestCase as TestCase;

class ZfcRbacServiceDecoratorFactoryTest extends TestCase
{
    public function testShouldCreateAZfcRbacService()
    {
        $authService = $this->getMockBuilder('ZfcRbac\\Service\\AuthorizationService')
                            ->disableOriginalConstructor()
                            ->getMock();
        $services    = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->any())
                 ->method('get')
                 ->with('ZfcRbac\\Service\\AuthorizationService')
                 ->will($this->returnValue($authService));

        $factory = new ZfcRbacServiceDecoratorFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Service\\ZfcRbacServiceDecorator',
            $factory->createService($services)
        );
    }
}
