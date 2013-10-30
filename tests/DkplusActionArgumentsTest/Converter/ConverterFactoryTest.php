<?php
namespace DkplusActionArgumentsTest\Converter;

use DkplusActionArguments\Converter\ConverterFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Converter\ConverterFactory
 */
class ConverterFactoryTest extends TestCase
{
    /** @var ConverterFactory */
    protected $factory;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $services;

    protected function setUp()
    {
        parent::setUp();
        $this->services = $this->getMockForAbstractClass('Zend\ServiceManager\ServiceLocatorInterface');
        $this->factory  = new ConverterFactory($this->services);
    }

    public function testShouldReturnACallbackConverterForArrays()
    {
        $result = $this->factory->create(array('myConverterService', 'myMethod'), 'stdClass');
        $this->assertInstanceOf('DkplusActionArguments\\Converter\\CallbackConverter', $result);
    }

    public function testShouldRetrieveTheServiceForTheCallbackConverterFromTheServiceLocator()
    {
        $this->services->expects($this->once())
                       ->method('get')
                       ->with('myConverterService');
        $this->factory->create(array('myConverterService', 'myMethod'), 'stdClass');
    }

    public function testShouldTryToRetrieveAConverterFromTheServiceLocator()
    {
        $converter = $this->getMockForAbstractClass('DkplusActionArguments\Converter\Converter');
        $this->services->expects($this->once())
                       ->method('has')
                       ->with('myConverterService')
                       ->will($this->returnValue(true));
        $this->services->expects($this->once())
                       ->method('get')
                       ->with('myConverterService')
                       ->will($this->returnValue($converter));

        $this->assertSame($converter, $this->factory->create('myConverterService', 'stdClass'));
    }

    public function testShouldTryToCreateADoctrineConverterForTheTargetEntity()
    {
        $repository    = $this->getMockForAbstractClass('Doctrine\\Common\\Persistence\\ObjectRepository');
        $objectManager = $this->getMockForAbstractClass('Doctrine\\Common\\Persistence\\ObjectManager');
        $objectManager->expects($this->once())
                      ->method('getRepository')
                      ->with('MyEntity')
                      ->will($this->returnValue($repository));

        $this->services->expects($this->once())
                       ->method('get')
                       ->with('Doctrine\\ORM\\EntityManager')
                       ->will($this->returnValue($objectManager));

        $result = $this->factory->create('find', 'MyEntity');
        $this->assertInstanceOf('DkplusActionArguments\\Converter\\DoctrineConverter', $result);
    }

    public function testShouldReturnNullIfNoConverterCanBeCreated()
    {
        $objectManager = $this->getMockForAbstractClass('Doctrine\\Common\\Persistence\\ObjectManager');
        $objectManager->expects($this->any())
                      ->method('getRepository')
                      ->will($this->returnValue(null));

        $this->services->expects($this->any())->method('has')->will($this->returnValue(false));
        $this->services->expects($this->any())
                       ->method('get')
                       ->with('Doctrine\\ORM\\EntityManager')
                       ->will($this->returnValue($objectManager));

        $this->assertNull($this->factory->create('foobar', 'string'));
    }
}
 