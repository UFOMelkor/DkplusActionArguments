<?php
namespace DkplusActionArgumentsTest\View;

use DkplusActionArguments\View\MissingArgumentsStrategyFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\View\MissingArgumentsStrategyFactory
 */
class MissingArgumentsStrategyFactoryTest extends TestCase
{
    public function testShouldCreateAStrategy()
    {
        $options  = $this->getMock('DkplusActionArguments\\Options\\ModuleOptions');
        $options->expects($this->once())
                ->method('getMissingArgumentsTemplate');
        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('DkplusActionArguments\\Options\\ModuleOptions')
                 ->will($this->returnValue($options));

        $factory = new MissingArgumentsStrategyFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\View\\MissingArgumentsStrategy',
            $factory->createService($services)
        );
    }
}
