<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\MethodConfigurationProviderFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Service\MethodConfigurationProviderFactory
 */
class MethodConfigurationProviderFactoryTest extends TestCase
{
    public function testShouldCreateAMethodConfigurationProvider()
    {
        $annotationBuilder = $this->getMockBuilder('DkplusActionArguments\\Annotation\\AnnotationBuilder')
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $methodFactory     = $this->getMockBuilder('DkplusActionArguments\\Configuration\\MethodFactory')
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $writer            = $this->getMockBuilder('DkplusActionArguments\\Specification\\Writer')
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $config            = array('DkplusActionArguments' => array());
        $services          = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->any())
                 ->method('get')
                 ->will($this->returnValueMap(array(
                     array('DkplusActionArguments\\Annotation\\AnnotationBuilder', $annotationBuilder),
                     array('DkplusActionArguments\\Configuration\\MethodFactory', $methodFactory),
                     array('DkplusActionArguments\\Specification\\Writer', $writer),
                     array('Config', $config)
                 )));


        $factory = new MethodConfigurationProviderFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Service\\MethodConfigurationProvider',
            $factory->createService($services)
        );
    }
}
