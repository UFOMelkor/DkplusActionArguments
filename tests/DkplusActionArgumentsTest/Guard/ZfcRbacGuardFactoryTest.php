<?php
namespace DkplusActionArgumentsTest\Guard;

use DkplusActionArguments\Guard\ZfcRbacGuardFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Guard\ZfcRbacGuardFactory
 */
class ZfcRbacGuardFactoryTest extends TestCase
{
    public function testShouldCreateAnRbacPermissionAssertionConnectorGuard()
    {
        $controllerManager = $this->getMock('Zend\\Mvc\\Controller\\ControllerManager');
        $argumentsService  = $this->getMockBuilder('DkplusActionArguments\\Service\\ArgumentsService')
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $connector         = $this->getMockForAbstractClass(
                                  'DkplusActionArguments\\Service\\RbacAssertionPermissionConnector');
        $services          = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap(array(
                array('ControllerLoader', $controllerManager),
                array('DkplusActionArguments\Service\ArgumentsService', $argumentsService),
                array('DkplusActionArguments\Service\ZfcRbacServiceDecorator', $connector)
            )));

        $factory = new ZfcRbacGuardFactory();
        $result  = $factory->createService($services);
        $this->assertInstanceOf('DkplusActionArguments\\Guard\\RbacPermissionAssertionConnectorGuard', $result);
    }
}
