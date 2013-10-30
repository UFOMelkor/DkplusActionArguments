<?php
namespace DkplusActionArgumentsTest;

use DkplusActionArguments\Configuration\MethodFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Configuration\MethodFactory
 */
class MethodFactoryTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $argumentFactory;
    /** @var MethodFactory */
    protected $methodFactory;

    protected function setUp()
    {
        parent::setUp();
        $this->argumentFactory = $this->getMockBuilder('DkplusActionArguments\\Configuration\\ArgumentFactory')
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->methodFactory   = new MethodFactory($this->argumentFactory);
    }

    public function testShouldCreateNewMethodConfigurations()
    {
        $this->assertInstanceOf(
             'DkplusActionArguments\\Configuration\\Method',
             $this->methodFactory->createConfiguration(array())
        );
    }

    public function testShouldTransferAnnotationsToMethod()
    {
        $method = $this->methodFactory->createConfiguration(array(
            'guards' => array(
                array('permission' => null,   'assertion' => 'my-assertion'),
                array('permission' => 'read', 'assertion' => 'another-assertion')
            )
         ));
        $this->assertArrayHasKey('read', $method->getAssertions());
        $this->assertContains('my-assertion', $method->getAssertions());
        $this->assertContains('another-assertion', $method->getAssertions());
    }

    public function testShouldTransferArgumentsToMethod()
    {
        $argumentSpec = array('source' => 'id', 'position' => 0, 'name' => 'foo', 'type' => 'int', 'optional' => false);
        $argumentMock = $this->getMockBuilder('DkplusActionArguments\\Configuration\\Argument')
                             ->disableOriginalConstructor()
                             ->getMock();
        $this->argumentFactory->expects($this->once())
                              ->method('createConfiguration')
                              ->with($argumentSpec)
                              ->will($this->returnValue($argumentMock));

        $method = $this->methodFactory->createConfiguration(array(
            'arguments' => array(
                $argumentSpec
            )
        ));
    }
}
 