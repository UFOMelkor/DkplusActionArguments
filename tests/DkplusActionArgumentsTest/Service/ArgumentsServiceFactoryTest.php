<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\ArgumentsServiceFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Service\ArgumentsServiceFactory
 */
class ArgumentsServiceFactoryTest extends TestCase
{
    public function testShouldCreateAnArgumentsService()
    {
        $configProvider = $this->getMockBuilder('DkplusActionArguments\\Service\\MethodConfigurationProvider')
                               ->disableOriginalConstructor()
                               ->getMock();
        $services       = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('DkplusActionArguments\\Service\\MethodConfigurationProvider')
                 ->will($this->returnValue($configProvider));


        $factory = new ArgumentsServiceFactory();
        $this->assertInstanceOf('DkplusActionArguments\\Service\\ArgumentsService', $factory->createService($services));
    }
}
