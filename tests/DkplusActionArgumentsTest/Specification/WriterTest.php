<?php
namespace DkplusActionArgumentsTest\Specification;

use DkplusActionArguments\Specification\Writer;
use PHPUnit_Framework_TestCase as TestCase;

class WriterTest extends TestCase
{
    /** @var string */
    protected $filePath;

    protected function setUp()
    {
        parent::setUp();
        $this->filePath = __DIR__ . '/TestAsset/spec.config.php';
    }

    protected function tearDown()
    {
        parent::tearDown();
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function testShouldWriteASpecifiactionIntoAFile()
    {
        $specification = array('foo' => 'bar');
        $writer        = new Writer($this->filePath);
        $writer->writeSpecification($specification);
        $this->assertEquals(
             array('DkplusActionArguments' => array('controllers' => $specification)),
             include $this->filePath
        );
    }
}
