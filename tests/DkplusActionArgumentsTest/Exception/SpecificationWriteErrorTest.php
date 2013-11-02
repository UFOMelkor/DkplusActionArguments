<?php
namespace DkplusActionArgumentsTest\Exception;

use DkplusActionArguments\Exception\SpecificationWriteError;
use PHPUnit_Framework_TestCase as TestCase;

class SpecificationWriteErrorTest extends TestCase
{
    public function testShouldPutTheFileNameIntoTheErrorMessage()
    {
        $exception = new SpecificationWriteError(__FILE__);
        $this->assertContains(__FILE__, $exception->getMessage());
    }
}
