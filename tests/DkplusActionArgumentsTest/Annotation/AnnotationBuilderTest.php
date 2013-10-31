<?php
namespace DkplusActionArgumentsTest\Annotation;

use DkplusActionArguments\Annotation\AnnotationBuilder;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;

/**
 * @covers DkplusActionArguments\Annotation\AnnotationBuilder
 */
class AnnotationBuilderTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $events;

    protected function setUp()
    {
        parent::setUp();
        $this->events = $this->getMockForAbstractClass('Zend\\EventManager\\EventManagerInterface');
    }

    public function testShouldRegisterGuardAnnotations()
    {
        $annotationManager = $this->getMock('Zend\\Code\\Annotation\\AnnotationManager');
        $parser            = $this->getMockForAbstractClass('Zend\\Code\\Annotation\\Parser\\ParserInterface');
        $parser->expects($this->at(0))
               ->method('registerAnnotation')
               ->with('DkplusActionArguments\\Annotation\\Guard');
        new AnnotationBuilder($this->events, $annotationManager, $parser);
    }

    public function testShouldRegisterMappingAnnotations()
    {
        $annotationManager = $this->getMock('Zend\\Code\\Annotation\\AnnotationManager');
        $parser            = $this->getMockForAbstractClass('Zend\\Code\\Annotation\\Parser\\ParserInterface');
        $parser->expects($this->at(1))
               ->method('registerAnnotation')
               ->with('DkplusActionArguments\\Annotation\\MapParam');
        new AnnotationBuilder($this->events, $annotationManager, $parser);
    }

    public function testShouldUseTheGivenParser()
    {
        $annotationManager = $this->getMock('Zend\\Code\\Annotation\\AnnotationManager');
        $parser            = $this->getMockForAbstractClass('Zend\\Code\\Annotation\\Parser\\ParserInterface');
        $annotationManager->expects($this->once())
                          ->method('attach')
                          ->with($parser);
        new AnnotationBuilder($this->events, $annotationManager, $parser);
    }

    public function testShouldProvideSpecificationAsArray()
    {
        $annotationManager = $this->getMock('Zend\\Code\\Annotation\\AnnotationManager');
        $parser            = $this->getMockForAbstractClass('Zend\\Code\\Annotation\\Parser\\ParserInterface');

        $builder = new AnnotationBuilder($this->events, $annotationManager, $parser);
        $result  = $builder->getMethodSpecification('DkplusActionArguments\\Configuration\\Argument', 'grabValue');
        $this->assertInternalType('array', $result);
    }

    public function testShouldProvideArgumentSpecification()
    {
        $annotationManager = new AnnotationManager();
        $parser            = new DoctrineAnnotationParser();

        $builder = new AnnotationBuilder($this->events, $annotationManager, $parser);
        $result  = $builder->getMethodSpecification(
            'DkplusActionArgumentsTest\\Annotation\\TestAsset\\Controller',
            'dummyAction'
        );

        $this->assertArrayHasKey('arguments', $result);
        return $result;
    }

    /**
     * @depends testShouldProvideArgumentSpecification
     */
    public function testShouldProvideTheArgumentType(array $result)
    {
        $this->assertEquals(
            'DkplusActionArgumentsTest\\Annotation\\TestAsset\\Controller',
            $result['arguments'][0]['type']
        );
    }

    /**
     * @depends testShouldProvideArgumentSpecification
     */
    public function testShouldProvideTheArgumentPosition(array $result)
    {
        $this->assertSame(0, $result['arguments'][0]['position']);
    }

    /**
     * @depends testShouldProvideArgumentSpecification
     */
    public function testShouldProvideArgumentName(array $result)
    {
        $this->assertSame('controller', $result['arguments'][0]['name']);
    }

    /**
     * @depends testShouldProvideArgumentSpecification
     */
    public function testShouldProvideOptionalArgument(array $result)
    {
        $this->assertSame(true, $result['arguments'][0]['optional']);
    }

    public function testShouldTriggerEventForEachAnnotationToConfigureMethod()
    {
        $annotationManager = new AnnotationManager();
        $parser            = new DoctrineAnnotationParser();
        $builder           = new AnnotationBuilder($this->events, $annotationManager, $parser);

        $this->events->expects($this->at(0))
                     ->method('trigger')
                     ->with('configureMethod', $builder, $this->isType('array'));

        $builder->getMethodSpecification(
            'DkplusActionArgumentsTest\\Annotation\\TestAsset\\Controller',
            'dummyAction'
        );
    }

    public function testShouldTriggerEventForEachArgumentAndAnnotationToConfigureArgument()
    {
        $annotationManager = new AnnotationManager();
        $parser            = new DoctrineAnnotationParser();
        $builder           = new AnnotationBuilder($this->events, $annotationManager, $parser);

        $this->events->expects($this->at(1))
                     ->method('trigger')
                     ->with('configureArgument', $builder, $this->isType('array'));

        $builder->getMethodSpecification(
            'DkplusActionArgumentsTest\\Annotation\\TestAsset\\Controller',
            'dummyAction'
        );
    }
}
