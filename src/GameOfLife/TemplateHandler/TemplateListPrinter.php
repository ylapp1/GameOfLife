<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use Util\FileSystem\FileSystemReader;

/**
 * Prints a list of default and custom template names.
 */
class TemplateListPrinter extends TemplateHandler
{
    // Attributes

    /**
     * The file system reader
     *
     * @var FileSystemReader $fileSystemReader
     */
    private $fileSystemReader;


    // Magic Methods

    /**
     * TemplateListPrinter constructor.
     *
     * @param String $_defaultTemplatesDirectory The directory in which the default templates are stored
     */
    public function __construct(String $_defaultTemplatesDirectory)
    {
        parent::__construct($_defaultTemplatesDirectory);
        $this->fileSystemReader = new FileSystemReader();
    }


    // Class Methods

    /**
     * Prints a list of default and custom template names.
     */
    public function printTemplateLists()
    {
        try
        {
            $defaultTemplateFilePaths = $this->fileSystemReader->getFileList($this->defaultTemplatesDirectory, "*.txt");
        }
        catch(\Exception $_exception)
        {
            $defaultTemplateFilePaths = array();
        }
        $this->printTemplateList("Default templates", $defaultTemplateFilePaths);

        try
        {
            $customTemplateFilePaths = $this->fileSystemReader->getFileList($this->customTemplatesDirectory, "*.txt");
        }
        catch(\Exception $_exception)
        {
            $customTemplateFilePaths = array();
        }
        $this->printTemplateList("Custom templates", $customTemplateFilePaths);
    }

    /**
     * Prints a list of template names.
     *
     * @param String $_title The title of the list
     * @param String[] $_templateFilePaths The list of template file paths
     */
    private function printTemplateList(String $_title, array $_templateFilePaths)
    {
        $outputString = "\n" . $_title . ":\n";
        if ($_templateFilePaths)
        {
            foreach ($_templateFilePaths as $index => $templateFilePath)
            {
                $outputString .= "  " . ($index + 1) . ") " . basename($templateFilePath, ".txt") . "\n";
            }
        }
        else $outputString .= "  None\n";

        echo $outputString;
    }
}
