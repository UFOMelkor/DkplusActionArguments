<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\SpiffyAssertionPermissionConnectorFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Service\SpiffyAssertionPermissionConnectorFactory
 */
class SpiffyAssertionPermissionConnectorFactoryTest extends TestCase
{
    public function testShouldCreateAConnector()
    {
        $rbacService = $this->getMock('SpiffyAuthorize\\Service\\RbacService');
        $services    = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('SpiffyAuthorize\\Service\\RbacService')
                 ->will($this->returnValue($rbacService));


        $factory = new SpiffyAssertionPermissionConnectorFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Service\\SpiffyAssertionPermissionConnector',
            $factory->createService($services)
        );
    }
}
