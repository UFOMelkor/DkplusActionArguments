<?php
namespace DkplusActionArgumentsTest\Specification;

use DkplusActionArguments\Specification\WriterFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Specification\WriterFactory
 */
class WriterFactoryTest extends TestCase
{
    public function testShouldCreateAWriter()
    {
        $options  = $this->getMock('DkplusActionArguments\\Options\\ModuleOptions');
        $options->expects($this->once())
                ->method('getCacheFilePath');
        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('DkplusActionArguments\\Options\\ModuleOptions')
                 ->will($this->returnValue($options));

        $factory = new WriterFactory();
        $this->assertInstanceOf(
             'DkplusActionArguments\\Specification\\Writer',
             $factory->createService($services)
        );
    }
}
 