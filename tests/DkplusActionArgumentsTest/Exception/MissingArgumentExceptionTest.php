<?php
namespace DkplusActionArgumentsTest\Exception;

use DkplusActionArguments\Exception\MissingArgumentException;
use PHPUnit_Framework_TestCase as TestCase;

class MissingArgumentExceptionTest extends TestCase
{
    public function testShouldProvideArgumentNames()
    {
        $arguments = array('foo', 'bar');
        $exception = new MissingArgumentException($arguments);
        $this->assertSame($arguments, $exception->getArgumentNames());
    }
}
