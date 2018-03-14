<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use Utils\FileSystemHandler;

/**
 * Prints a list of default and custom templates.
 */
class TemplateListPrinter
{
    /**
     * The file system handler
     *
     * @var FileSystemHandler $fileSystemHandler
     */
    private $fileSystemHandler;

    /**
     * The base directory for default and custom templates
     *
     * @var String $templatesBaseDirectory
     */
    private $templatesBaseDirectory;


    /**
     * TemplateListPrinter constructor.
     *
     * @param String $_templatesBaseDirectory The base directory for default and custom templates
     */
    public function __construct(String $_templatesBaseDirectory)
    {
        $this->fileSystemHandler = new FileSystemHandler();
        $this->templatesBaseDirectory = $_templatesBaseDirectory;
    }


    /**
     * Prints a list of default and custom templates.
     */
    public function printTemplateLists()
    {
        $defaultTemplates = $this->fileSystemHandler->getFileList($this->templatesBaseDirectory . "/*.txt");
        $customTemplates = $this->fileSystemHandler->getFileList($this->templatesBaseDirectory . "/Custom/*.txt");

        $this->printTemplateList("Default templates", $defaultTemplates);
        $this->printTemplateList("Custom templates", $customTemplates);
    }

    /**
     * Generates an output string from a list of templates.
     *
     * @param String $_title The title of the list
     * @param String[] $_templateFilePaths The list of template file paths
     */
    private function printTemplateList(String $_title, array $_templateFilePaths)
    {
        $outputString = "\n" . $_title . ":\n";
        if (count($_templateFilePaths) == 0) $outputString .= "  None\n";
        else
        {
            foreach ($_templateFilePaths as $index => $templateFilePath)
            {
                $outputString .= "  " . ($index + 1) . ") " . basename($templateFilePath, ".txt") . "\n";
            }
        }

        echo $outputString;
    }
}
