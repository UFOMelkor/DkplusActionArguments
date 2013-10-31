<?php
namespace DkplusActionArgumentsTest\Converter;

use DkplusActionArguments\Converter\ConverterServiceFactory;
use PHPUnit_Framework_TestCase as TestCase;

class ConverterServiceFactoryTest extends TestCase
{
    public function testShouldCreateAConverterFactory()
    {
        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $factory  = new ConverterServiceFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Converter\\ConverterFactory',
            $factory->createService($services)
        );
    }
}
