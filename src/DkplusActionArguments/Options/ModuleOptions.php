<?php
namespace DkplusActionArguments\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Provides an interface for the module options.
 */
class ModuleOptions extends AbstractOptions
{
    /** @var string[] */
    private $guards = array();
    /** @var string */
    private $cacheFilePath;
    /** @var string */
    private $missingArgumentsTemplate = '';

    /** @return string[] The service manager keys of all guards that should be activated. */
    public function getGuards()
    {
        return $this->guards;
    }

    /**
     * @param string[] $guards
     * @return void
     */
    public function setGuards(array $guards)
    {
        $this->guards = $guards;
    }

    /** @return string The path to the file that is used to cache the specification. */
    public function getCacheFilePath()
    {
        return $this->cacheFilePath;
    }

    /**
     * @param string $cacheFilePath
     * @return void
     */
    public function setCacheFilePath($cacheFilePath)
    {
        $this->cacheFilePath = $cacheFilePath;
    }

    /**
     * @param string $template
     * @return void
     */
    public function setMissingArgumentsTemplate($template)
    {
        $this->missingArgumentsTemplate = $template;
    }

    /** @return string The template that will be used one or more argument could not be converted correct. */
    public function getMissingArgumentsTemplate()
    {
        return $this->missingArgumentsTemplate;
    }
}
