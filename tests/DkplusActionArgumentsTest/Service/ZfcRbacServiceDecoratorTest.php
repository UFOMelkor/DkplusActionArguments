<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\ZfcRbacServiceDecorator;
use PHPUnit_Framework_TestCase as TestCase;

class ZfcRbacServiceDecoratorTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $rbac;

    /** @var ZfcRbacServiceDecorator */
    protected $decorator;

    protected function setUp()
    {
        parent::setUp();
        $this->rbac      = $this->getMockBuilder('ZfcRbac\\Service\\AuthorizationService')
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->decorator = new ZfcRbacServiceDecorator($this->rbac);
    }

    public function testShouldAllowToRegisterAssertionsForPermissions()
    {
        $assertion = $this->getMockForAbstractClass('ZfcRbac\\Assertion\\AssertionInterface');
        $this->decorator->connect('write', $assertion);

        $this->rbac->expects($this->once())
                   ->method('isGranted')
                   ->with('write', $assertion)
                   ->will($this->returnValue(true));

        $this->assertTrue($this->decorator->isGranted('write'));
    }

    public function testShouldStillAllowToPassAnAssertion()
    {
        $registeredAssertion = $this->getMockForAbstractClass('ZfcRbac\\Assertion\\AssertionInterface');
        $passedAssertion     = $this->getMockForAbstractClass('ZfcRbac\\Assertion\\AssertionInterface');

        $this->decorator->connect('write', $registeredAssertion);

        $this->rbac->expects($this->once())
                   ->method('isGranted')
                   ->with('write', $passedAssertion)
                   ->will($this->returnValue(false));

        $this->assertFalse($this->decorator->isGranted('write', $passedAssertion));
    }

    public function testShouldStillAllowToOmitTheAssertion()
    {
        $this->rbac->expects($this->once())
                   ->method('isGranted')
                   ->with('write')
                   ->will($this->returnValue(true));

        $this->assertTrue($this->decorator->isGranted('write'));
    }
}
