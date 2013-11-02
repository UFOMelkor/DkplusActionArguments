<?php
namespace DkplusActionArguments\Specification;

use DkplusActionArguments\Exception\SpecificationWriteError;

/**
 * Writes the complete specification for controller-actions as php array into a file.
 */
class Writer
{
    /** @var string */
    private $targetFilePath;

    /** @param string $targetFilePath */
    public function __construct($targetFilePath)
    {
        $this->targetFilePath = $targetFilePath;
    }

    /**
     * @param array $specification
     * @return void
     * @throws SpecificationWriteError in case it cannot write into the file.
     * @link http://de.php.net/manual/en/function.file-put-contents.php#82934
     */
    public function writeSpecification(array $specification)
    {
        $content = var_export(array('DkplusActionArguments' => array('controllers' => $specification)), true);
        if (@file_put_contents($this->targetFilePath . '.tmp', "<?php\nreturn " . $content . ';') === false) {
            throw new SpecificationWriteError($this->targetFilePath);
        }
        rename($this->targetFilePath . '.tmp', $this->targetFilePath);
    }
}
