<?php
namespace DkplusActionArgumentsTest\Guard;

use DkplusActionArguments\Guard\GuardsFactory;
use PHPUnit_Framework_TestCase as TestCase;

class GuardsFactoryTest extends TestCase
{
    public function testShouldProvideConfiguredGuards()
    {
        $options = $this->getMock('DkplusActionArguments\\Options\\ModuleOptions');
        $options->expects($this->once())
                ->method('getGuards')
                ->will($this->returnValue(array('myGuard')));

        $guard = $this->getMock('stdClass');

        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->any())
                 ->method('get')
                 ->will($this->returnValueMap(array(
                                                   array('DkplusActionArguments\\Options\\ModuleOptions', $options),
                                                   array('myGuard', $guard)
                                              )));

        $factory = new GuardsFactory();
        $this->assertSame(array($guard), $factory->createService($services));
    }
}
