<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Field;
use Utils\FileSystem\FileSystemReader;

/**
 * Loads template fields from a file.
 */
class TemplateLoader extends TemplateHandler
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
     * TemplateLoader constructor.
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
     * Loads template fields from a file.
     *
     * @param String $_templateName The name of the template
     *
     * @return Field[][] The loaded template fields
     *
     * @throws \Exception The exception when the template file was not found or could not be read
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
        $lines = $this->fileSystemReader->readFile($fileName);

        $fields = array();
        foreach ($lines as $y => $line)
        {
            $fields[$y] = array();
            $lineCharacters = str_split($line);

            foreach ($lineCharacters as $x => $lineCharacter)
            {
                if ($lineCharacter == "X") $cellState = true;
                else $cellState = false;

                $fields[$y][$x] = new Field($x, $y, $cellState);
            }
        }

        return $fields;
    }
}
