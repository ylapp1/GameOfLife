<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

/**
 * Prints a list of default and custom templates.
 */
class TemplateListPrinter extends TemplateHandler
{
    /**
     * TemplateListPrinter constructor.
     *
     * @param String $_templatesBaseDirectory The base directory for default and custom templates
     */
    public function __construct(String $_templatesBaseDirectory)
    {
        parent::__construct($_templatesBaseDirectory);
    }


    /**
     * Prints a list of default and custom templates.
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
