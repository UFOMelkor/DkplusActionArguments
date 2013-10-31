<?php
namespace DkplusActionArgumentsTest\Annotation;

use DkplusActionArguments\Annotation\AnnotationListener;
use DkplusActionArguments\Annotation\Guard;
use DkplusActionArguments\Annotation\MapParam;

class AnnotationListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AnnotationListener */
    protected $listener;

    protected function setUp()
    {
        $this->listener = new AnnotationListener();
    }

    public function testShouldAttachConfigureGuard()
    {
        $callbackMock = $this->getMock('Zend\\Stdlib\\CallbackHandler', array(), array(), '', false);
        $eventsMock   = $this->getMock('Zend\\EventManager\\EventManagerInterface');
        $eventsMock->expects($this->at(0))
                   ->method('attach')
                   ->with('configureMethod', array($this->listener, 'configureGuard'))
                   ->will($this->returnValue($callbackMock));

        $this->listener->attach($eventsMock);
    }

    /**
     * @depends testShouldAttachConfigureGuard
     */
    public function testCanDetachConfigureGuard()
    {
        $callbackMock = $this->getMock('Zend\\Stdlib\\CallbackHandler', array(), array(), '', false);
        $eventsMock   = $this->getMock('Zend\\EventManager\\EventManagerInterface');
        $eventsMock->expects($this->at(0))->method('attach')->will($this->returnValue($callbackMock));
        $eventsMock->expects($this->at(2))->method('detach')->with($callbackMock);

        $this->listener->attach($eventsMock);
        $this->listener->detach($eventsMock);
    }

    public function testShouldAttachConfigureMapping()
    {
        $callbackMock = $this->getMock('Zend\\Stdlib\\CallbackHandler', array(), array(), '', false);
        $eventsMock   = $this->getMock('Zend\\EventManager\\EventManagerInterface');
        $eventsMock->expects($this->at(1))
                   ->method('attach')
                   ->with('configureArgument', array($this->listener, 'configureMapping'))
                   ->will($this->returnValue($callbackMock));

        $this->listener->attach($eventsMock);
    }

    /**
     * @depends testShouldAttachConfigureMapping
     */
    public function testCanDetachConfigureMapping()
    {
        $callbackMock = $this->getMock('Zend\\Stdlib\\CallbackHandler', array(), array(), '', false);
        $eventsMock   = $this->getMock('Zend\\EventManager\\EventManagerInterface');
        $eventsMock->expects($this->at(1))->method('attach')->will($this->returnValue($callbackMock));
        $eventsMock->expects($this->at(3))->method('detach')->with($callbackMock);

        $this->listener->attach($eventsMock);
        $this->listener->detach($eventsMock);
    }

    public function testShouldIgnoreOtherAnnotationsOnGuardConfiguration()
    {
        $annotation = new MapParam();
        $spec       = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $spec->expects($this->never())->method('offsetGet');

        $this->listener->configureGuard($this->createEventMock($annotation, $spec));
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|null $annotation
     * @param \PHPUnit_Framework_MockObject_MockObject|null $spec
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createEventMock($annotation = null, $spec = null)
    {
        $map = array(
            array('annotation', null, $annotation),
            array('spec', null, $spec)
        );

        $result = $this->getMock('Zend\\EventManager\\EventInterface');
        $result->expects($this->any())
               ->method('getParam')
               ->will($this->returnValueMap($map));
        $this->assertSame($annotation, $result->getParam('annotation'));
        $this->assertSame($spec, $result->getParam('spec'));
        return $result;
    }

    public function testShouldAddGuardToSpecification()
    {
        $annotation = new Guard();
        $annotation->assertion  = 'my-assertion';
        $annotation->permission = 'read-write';

        $guardsSpec = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $guardsSpec->expects($this->once())
                   ->method('offsetSet')
                   ->with(null, array('assertion' => 'my-assertion', 'permission' => 'read-write'));

        $spec = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $spec->expects($this->any())->method('offsetGet')->will($this->returnValue($guardsSpec));

        $this->listener->configureGuard($this->createEventMock($annotation, $spec));
    }

    public function testShouldIgnoreOtherAnnotationsOnMappingConfiguration()
    {
        $annotation = new Guard();
        $spec       = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $spec->expects($this->never())->method('offsetSet');

        $this->listener->configureMapping($this->createEventMock($annotation, $spec));
    }

    public function testShouldIgnoreMappingAnnotationsOfOtherArguments()
    {
        $annotation     = new MapParam();
        $annotation->to = 'one-argument';
        $spec           = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $spec->expects($this->once())->method('offsetGet')->with('name')->will($this->returnValue('another-argument'));
        $spec->expects($this->never())->method('offsetSet');

        $this->listener->configureMapping($this->createEventMock($annotation, $spec));
    }

    public function testShouldSetSourceValueForArguments()
    {
        $annotation       = new MapParam();
        $annotation->from = 'my-param';
        $spec             = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $spec->expects($this->at(1))->method('offsetSet')->with('source', 'my-param');

        $this->listener->configureMapping($this->createEventMock($annotation, $spec));
    }

    public function testShouldSetSourceValueToArgumentNameOnFallbackForArguments()
    {
        $annotation     = new MapParam();
        $annotation->to = 'my-param';
        $spec           = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $spec->expects($this->any())->method('offsetGet')->with('name')->will($this->returnValue('my-param'));
        $spec->expects($this->at(1))->method('offsetSet')->with('source', 'my-param');

        $this->listener->configureMapping($this->createEventMock($annotation, $spec));
    }

    public function testShouldSetConverterValueForArguments()
    {
        $annotation        = new MapParam();
        $annotation->using = 'my-converter';
        $spec              = $this->getMock('Zend\\Stdlib\\ArrayObject');
        $spec->expects($this->at(2))->method('offsetSet')->with('converter', 'my-converter');

        $this->listener->configureMapping($this->createEventMock($annotation, $spec));
    }
}
