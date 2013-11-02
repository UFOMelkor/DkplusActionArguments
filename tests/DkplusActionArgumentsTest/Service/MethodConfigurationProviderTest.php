<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\MethodConfigurationProvider;
use PHPUnit_Framework_TestCase as TestCase;

class MethodConfigurationProviderTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $annotationBuilder;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $configurationFactory;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $specWriter;

    protected function setUp()
    {
        parent::setUp();
        $this->annotationBuilder    = $this->getMockBuilder('DkplusActionArguments\\Annotation\\AnnotationBuilder')
                                           ->disableOriginalConstructor()
                                           ->getMock();
        $this->configurationFactory = $this->getMockBuilder('DkplusActionArguments\\Configuration\\MethodFactory')
                                           ->disableOriginalConstructor()
                                           ->getMock();
        $this->specWriter           = $this->getMockBuilder('DkplusActionArguments\\Specification\\Writer')
                                           ->disableOriginalConstructor()
                                           ->getMock();
    }

    public function testShouldBuildNewConfigurationsOnTheFly()
    {
        $controller = $this->getMock('stdClass');
        $service    = new MethodConfigurationProvider(
            $this->annotationBuilder,
            $this->configurationFactory,
            $this->specWriter,
            array()
        );

        $specification = array('foo' => 'bar');
        $this->annotationBuilder->expects($this->once())
                                ->method('getMethodSpecification')
                                ->with(get_class($controller), 'indexAction')
                                ->will($this->returnValue($specification));

        $configuration = $this->getMock('DkplusActionArguments\\Configuration\\Method');
        $this->configurationFactory->expects($this->once())
                                   ->method('createConfiguration')
                                   ->with($specification)
                                   ->will($this->returnValue($configuration));

        $this->assertSame($configuration, $service->computeMethodConfiguration($controller, 'indexAction'));
    }

    public function testShouldWriteBuildSpecificationsIntoFiles()
    {
        $controller = $this->getMock('stdClass');
        $service    = new MethodConfigurationProvider(
            $this->annotationBuilder,
            $this->configurationFactory,
            $this->specWriter,
            array()
        );

        $specification = array('foo' => 'bar');
        $this->annotationBuilder->expects($this->once())
                                ->method('getMethodSpecification')
                                ->will($this->returnValue($specification));

        $this->specWriter->expects($this->once())
                         ->method('writeSpecification')
                         ->with(array(get_class($controller) => array('indexAction' => $specification)));

        $service->computeMethodConfiguration($controller, 'indexAction');
    }

    public function testShouldUseGeneratedSpecifications()
    {
        $controller     = $this->getMock('stdClass');
        $specification  = array('foo' => 'bar');
        $specifications = array(get_class($controller) => array('indexAction' => $specification));
        $service        = new MethodConfigurationProvider(
            $this->annotationBuilder,
            $this->configurationFactory,
            $this->specWriter,
            $specifications
        );

        $this->configurationFactory->expects($this->once())
                                   ->method('createConfiguration')
                                   ->with($specification);

        $service->computeMethodConfiguration(get_class($controller), 'indexAction');
    }

    public function testShouldCreateConfigurationsOnlyOnce()
    {
        $controller     = $this->getMock('stdClass');
        $specifications = array(get_class($controller) => array('indexAction' => array('foo' => 'bar')));
        $service        = new MethodConfigurationProvider(
            $this->annotationBuilder,
            $this->configurationFactory,
            $this->specWriter,
            $specifications
        );

        $configuration = $this->getMock('DkplusActionArguments\\Configuration\\Method');
        $this->configurationFactory->expects($this->once())
                                   ->method('createConfiguration')
                                   ->will($this->returnValue($configuration));

        $this->assertSame($configuration, $service->computeMethodConfiguration($controller, 'indexAction'));
        $this->assertSame($configuration, $service->computeMethodConfiguration($controller, 'indexAction'));
    }
}
 