<?php
namespace DkplusActionArgumentsTest\Guard;

use DkplusActionArguments\Guard\ArgumentsGuardFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Guard\ArgumentsGuardFactory
 */
class ArgumentsGuardFactoryTest extends TestCase
{
    public function testShouldCreateAnArgumentGuard()
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

        $factory = new ArgumentsGuardFactory();
        $this->assertInstanceOf('DkplusActionArguments\\Guard\\ArgumentsGuard', $factory->createService($services));
    }
}
