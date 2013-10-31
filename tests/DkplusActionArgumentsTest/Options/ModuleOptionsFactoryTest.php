<?php
namespace DkplusActionArgumentsTest\Options;

use DkplusActionArguments\Options\ModuleOptionsFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Options\ModuleOptionsFactory
 */
class ModuleOptionsFactoryTest extends TestCase
{
    public function testShouldCreateModuleOptions()
    {
        $config   = array('DkplusActionArguments' => array('options' => array('guards' => array())));
        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('Config')
                 ->will($this->returnValue($config));

        $factory = new ModuleOptionsFactory();
        $this->assertInstanceOf('DkplusActionArguments\\Options\\ModuleOptions', $factory->createService($services));
    }
}
