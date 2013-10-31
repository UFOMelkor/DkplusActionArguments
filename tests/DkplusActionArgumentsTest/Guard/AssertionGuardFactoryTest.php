<?php
namespace DkplusActionArgumentsTest\Guard;

use DkplusActionArguments\Guard\AssertionGuardFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Guard\AssertionGuardFactory
 */
class AssertionGuardFactoryTest extends TestCase
{
    public function testShouldCreateAnAssertionGuard()
    {
        $controllerManager = $this->getMock('Zend\\Mvc\\Controller\\ControllerManager');
        $argumentsService  = $this->getMockBuilder('DkplusActionArguments\\Service\\ArgumentsService')
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $services          = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->any())
                 ->method('get')
                 ->will($this->returnValueMap(array(
                    array('ControllerLoader', $controllerManager),
                    array('DkplusActionArguments\Service\ArgumentsService', $argumentsService)
                 )));

        $factory = new AssertionGuardFactory();
        $this->assertInstanceOf('DkplusActionArguments\\Guard\\AssertionGuard', $factory->createService($services));
    }
}
