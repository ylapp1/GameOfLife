<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Board;
use GameOfLife\Field;

/**
 * Loads templates from files.
 */
class TemplateLoader extends TemplateHandler
{
    /**
     * TemplateLoader constructor.
     *
     * @param String $_templateDirectory Template base directory
     */
    public function __construct(String $_templateDirectory)
    {
        parent::__construct($_templateDirectory);
    }

    /**
     * Loads a template from a file.
     *
     * @param Board $_board Parent board of the fields
     * @param String $_templateName Name of the template
     *
     * @return Template|bool Loaded template or false
     */
    public function loadTemplate(Board $_board, String $_templateName)
    {
        $fileNameOfficial =  $this->templateDirectory . "/" . $_templateName . ".txt";
        $fileNameCustom = $this->templateDirectory . "/Custom/" . $_templateName . ".txt";

        // check whether template exists
        if (file_exists($fileNameOfficial)) $fileName = $fileNameOfficial;
        elseif (file_exists($fileNameCustom)) $fileName = $fileNameCustom;
        else return false;

        // Read template
        $lines = $this->fileSystemHandler->readFile($fileName);

        $fields = array();
        for ($y = 0; $y < count($lines); $y++)
        {
            $fields[] = array();
            $row = str_split($lines[$y]);

            for ($x = 0; $x < count($row); $x++)
            {
                $field = new Field($_board, $x, $y);
                if ($row[$x] == "X") $field->setValue(true);

                $fields[$y][] = $field;
            }
        }

        return new Template($fields);
    }
}