<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\SpiffyAssertionPermissionConnector;
use PHPUnit_Framework_TestCase as TestCase;

class SpiffyAssertionPermissionConnectorTest extends TestCase
{
    public function testShouldRegisterAnnotationsForPermissions()
    {
        $assertion = $this->getMockForAbstractClass('Zend\\Permissions\\Rbac\\AssertionInterface');
        $rbac      = $this->getMock('SpiffyAuthorize\\Service\\RbacService');
        $rbac->expects($this->once())
             ->method('registerAssertion')
             ->with('write', $assertion);

        $connector = new SpiffyAssertionPermissionConnector($rbac);

        $connector->connect('write', $assertion);
    }
}
