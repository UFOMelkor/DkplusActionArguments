<?php
namespace DkplusActionArgumentsTest;

use DkplusActionArguments\Configuration\ArgumentFactory;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Configuration\ArgumentFactory
 */
class ArgumentFactoryTest extends TestCase
{
    /** @var ArgumentFactory */
    protected $argumentFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $converterFactory;

    protected function setUp()
    {
        parent::setUp();
        $this->converterFactory = $this->getMockBuilder('DkplusActionArguments\\Converter\\ConverterFactory')
                                       ->disableOriginalConstructor()
                                       ->getMock();
        $this->argumentFactory  = new ArgumentFactory($this->converterFactory);
    }

    public function testShouldCreateArgumentConfigurations()
    {
        $spec = array(
            'source'    => 'id',
            'position'  => 0,
            'name'      => 'foo',
            'type'      => 'string',
            'optional'  => true,
            'converter' => null,
        );
        $this->assertInstanceOf(
             'DkplusActionArguments\\Configuration\\Argument',
             $this->argumentFactory->createConfiguration($spec)
        );
    }

    public function testShouldCreateConvertersForArgumentConfigurations()
    {
        $spec = array(
            'source'    => 'name',
            'position'  => 0,
            'name'      => 'user',
            'type'      => 'User',
            'optional'  => true,
            'converter' => 'findOneByName',
        );
        $this->converterFactory->expects($this->once())->method('create')->with('findOneByName', 'User');
        $this->argumentFactory->createConfiguration($spec);
    }
}
