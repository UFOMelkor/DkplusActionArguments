<?php
namespace DkplusActionArgumentsTest\Options;

use DkplusActionArguments\Options\ModuleOptions;
use PHPUnit_Framework_TestCase as TestCase;

class ModuleOptionsTest extends TestCase
{
    /** @var ModuleOptions */
    protected $moduleOptions;

    protected function setUp()
    {
        parent::setUp();
        $this->moduleOptions = new ModuleOptions();
    }

    public function testShouldProvideGuardNames()
    {
        $guards = array('MyGuard');
        $this->moduleOptions->setGuards($guards);
        $this->assertSame($guards, $this->moduleOptions->getGuards());
    }

    public function testShouldProvideCacheFilePath()
    {
        $path = __DIR__ . '/cache.php';
        $this->moduleOptions->setCacheFilePath($path);
        $this->assertSame($path, $this->moduleOptions->getCacheFilePath());
    }

    public function testShouldProvideMissingArgumentsTemplate()
    {
        $template = 'error/404-missing-arguments';
        $this->moduleOptions->setMissingArgumentsTemplate($template);
        $this->assertSame($template, $this->moduleOptions->getMissingArgumentsTemplate());
    }
}
