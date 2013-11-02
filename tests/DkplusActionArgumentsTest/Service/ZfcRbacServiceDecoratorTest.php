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
        $this->rbac      = $this->getMock('ZfcRbac\\Service\\Rbac');
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

    public function testShouldLeaveTheSetEventManagerMethodUndecorated()
    {
        $events = $this->getMockForAbstractClass('Zend\\EventManager\\EventManagerInterface');
        $this->rbac->expects($this->once())
                   ->method('setEventManager')
                   ->with($events);

        $this->assertSame($this->decorator, $this->decorator->setEventManager($events));
    }

    public function testShouldLeaveTheGetEventManagerMethodUndecorated()
    {
        $events = $this->getMockForAbstractClass('Zend\\EventManager\\EventManagerInterface');
        $this->rbac->expects($this->once())
                   ->method('getEventManager')
                   ->will($this->returnValue($events));

        $this->assertSame($events, $this->decorator->getEventManager());
    }

    public function testShouldLeaveTheGetRoleMethodUndecorated()
    {
        $role = 'myRole';
        $this->rbac->expects($this->once())
                   ->method('hasRole')
                   ->with($role)
                   ->will($this->returnValue(true));

        $this->assertTrue($this->decorator->hasRole($role));
    }

    public function testShouldLeaveTheGetFirewallMethodUndecorated()
    {
        $name     = 'myFirewall';
        $firewall = $this->getMockForAbstractClass('ZfcRbac\\Firewall\\AbstractFirewall');
        $this->rbac->expects($this->once())
                   ->method('getFirewall')
                   ->with($name)
                   ->will($this->returnValue($firewall));

        $this->assertSame($firewall, $this->decorator->getFirewall($name));
    }

    public function testShouldLeaveTheAddFirewallMethodUndecorated()
    {
        $firewall = $this->getMockForAbstractClass('ZfcRbac\\Firewall\\AbstractFirewall');
        $this->rbac->expects($this->once())
                   ->method('addFirewall')
                   ->with($firewall);

        $this->assertSame($this->decorator, $this->decorator->addFirewall($firewall));
    }

    public function testShouldLeaveTheAddProviderMethodUndecorated()
    {
        $provider = $this->getMockForAbstractClass('ZfcRbac\\Provider\\ProviderInterface');
        $this->rbac->expects($this->once())
                   ->method('addProvider')
                   ->with($provider);

        $this->assertSame($this->decorator, $this->decorator->addProvider($provider));
    }

    public function testShouldLeaveTheGetIdentityMethodUndecorated()
    {
        $identity = $this->getMockForAbstractClass('ZfcRbac\\Identity\\IdentityInterface');
        $this->rbac->expects($this->once())
                   ->method('getIdentity')
                   ->will($this->returnValue($identity));

        $this->assertSame($identity, $this->decorator->getIdentity());
    }

    public function testShouldLeaveTheSetIdentityMethodUndecorated()
    {
        $identity = $this->getMockForAbstractClass('ZfcRbac\\Identity\\IdentityInterface');
        $this->rbac->expects($this->once())
                   ->method('setIdentity')
                   ->with($identity);

        $this->assertSame($this->decorator, $this->decorator->setIdentity($identity));
    }

    public function testShouldLeaveTheGetRbacMethodUndecorated()
    {
        $rbac = $this->getMock('Zend\\Permissions\\Rbac\\Rbac');
        $this->rbac->expects($this->once())
                   ->method('getRbac')
                   ->will($this->returnValue($rbac));

        $this->assertSame($rbac, $this->decorator->getRbac());
    }

    public function testShouldLeaveTheGetOptionsMethodUndecorated()
    {
        $options = $this->getMock('Zfc\\Rbac\\RbacOptions');
        $this->rbac->expects($this->once())
                   ->method('getOptions')
                   ->will($this->returnValue($options));

        $this->assertSame($options, $this->decorator->getOptions());
    }
}
