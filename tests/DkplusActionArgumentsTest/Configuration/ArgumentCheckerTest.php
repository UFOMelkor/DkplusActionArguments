<?php
namespace DkplusActionArgumentsTest;

use DkplusActionArguments\Configuration\ArgumentChecker;
use PHPUnit_Framework_TestCase as TestCase;

class ArgumentCheckerTest extends TestCase
{
    /** @var ArgumentChecker */
    protected $checker;

    public function testShouldAllowNullIfOptional()
    {
        $checker = new ArgumentChecker('string', true);
        $this->assertFalse($checker->isMissing(null));
    }

    public function testShouldDenyNullIfNotOptional()
    {
        $checker = new ArgumentChecker('string', false);
        $this->assertTrue($checker->isMissing(null));
    }

    public function testShouldAllowTypes()
    {
        $checker = new ArgumentChecker('string', false);
        $this->assertFalse($checker->isMissing('foo'));
    }

    public function testShouldAllowClassNames()
    {
        $checker = new ArgumentChecker('stdClass', false);
        $this->assertFalse($checker->isMissing(new \stdClass()));
    }
}
