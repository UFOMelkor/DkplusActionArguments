<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\ZfcRbacServiceDecoratorFactory;
use PHPUnit_Framework_TestCase as TestCase;

class ZfcRbacServiceDecoratorFactoryTest extends TestCase
{
    public function testShouldCreateAZfcRbacService()
    {
        $options = array(
            'identityProvider' => 'myIdentity'
        );
        $services = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $services->expects($this->any())->method('has')->with('myIdentity')->will($this->returnValue(true));
        $services->expects($this->any())
                 ->method('get')
                 ->will($this->returnValueMap(array(
                                                   array('Configuration', array('zfcrbac' => $options)),
                                                   array('identity', 'myIdentity'),
                                              )));

        $factory = new ZfcRbacServiceDecoratorFactory();
        $this->assertInstanceOf(
            'DkplusActionArguments\\Service\\ZfcRbacServiceDecorator',
            $factory->createService($services)
        );
    }
}
