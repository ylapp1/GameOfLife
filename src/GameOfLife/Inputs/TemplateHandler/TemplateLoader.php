<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Field;

/**
 * Loads templates from files.
 */
class TemplateLoader extends TemplateHandler
{
    /**
     * TemplateLoader constructor.
     *
     * @param String $_templatesBaseDirectory The base directory for default and custom templates
     */
    public function __construct(String $_templatesBaseDirectory)
    {
        parent::__construct($_templatesBaseDirectory);
    }

    /**
     * Loads a template from a file.
     *
     * @param String $_templateName The name of the template
     *
     * @return array The loaded template
     *
     * @throws \Exception The exception when the template file was not found or the file could not be read
     */
    public function loadTemplate(String $_templateName)
    {
        $fileName = $_templateName . ".txt";

        $defaultTemplatePath = $this->defaultTemplatesDirectory . "/" . $fileName;
        $customTemplatePath = $this->customTemplatesDirectory . "/" . $fileName;

        // Check whether the specified template exists
        if (file_exists($defaultTemplatePath)) $fileName = $defaultTemplatePath;
        elseif (file_exists($customTemplatePath)) $fileName = $customTemplatePath;
        else throw new \Exception("The template file could not be found.");

        // Read the template
        $lines = $this->fileSystemHandler->readFile($fileName);

        $fields = array();
        for ($y = 0; $y < count($lines); $y++)
        {
            $fields[] = array();
            $row = str_split($lines[$y]);

            for ($x = 0; $x < count($row); $x++)
            {
                if ($row[$x] == "X") $cellState = true;
                else $cellState = false;

                $fields[$y][] = new Field($x, $y, $cellState);
            }
        }

        return $fields;
    }
}
