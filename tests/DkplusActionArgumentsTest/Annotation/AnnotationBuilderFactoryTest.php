<?php
namespace DkplusActionArgumentsTest\Annotation;

use DkplusActionArguments\Annotation\AnnotationBuilderFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Annotation\AnnotationBuilderFactory
 */
class AnnotationBuilderFactoryTest extends TestCase
{
    public function testShouldCreateAnAnnotationBuilder()
    {
        $events = $this->getMockForAbstractClass('Zend\\EventManager\\EventManagerInterface');

        $application = $this->getMockForAbstractClass('Zend\\Mvc\\ApplicationInterface');
        $application->expects($this->once())
                    ->method('getEventManager')
                    ->will($this->returnValue($events));

        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->once())
                 ->method('get')
                 ->with('Application')
                 ->will($this->returnValue($application));

        $factory = new AnnotationBuilderFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Annotation\\AnnotationBuilder',
            $factory->createService($services)
        );
    }
}
 