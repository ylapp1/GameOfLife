<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * BaseOutput from which all other outputs must inherit.
 *
 * addOptions() adds options to a Getopt object
 * startOutput() initializes variables that are necessary for the output
 * outputBoard() outputs a single board
 * finishOutput() processes the output boards to create the final file
 */
class BaseOutput
{
    /**
     * Output directory for file outputs.
     *
     * @var String $outputDirectory
     */
    protected $outputDirectory = __DIR__ . "/../../../Output/";


    /**
     * Returns the output directory of the output.
     *
     * @return String Output directory of the output
     */
    public function outputDirectory(): String
    {
        return $this->outputDirectory;
    }

    /**
     * Sets the output directory of the output.
     *
     * @param String $_outputDirectory Output directory of the output
     */
    public function setOutputDirectory(String $_outputDirectory)
    {
        $this->outputDirectory = $_outputDirectory;
    }

    /**
     * Adds output specific options to the option list.
     *
     * @param Getopt $_options Current option list
     *
     * @codeCoverageIgnore
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Start output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     *
     * @codeCoverageIgnore
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
    }

    /**
     * Output one game step.
     *
     * @param Board $_board Current board
     *
     * @codeCoverageIgnore
     */
    public function outputBoard(Board $_board)
    {
    }

    /**
     * Finish output (Display that simulation is finished, write files and delete temporary files).
     *
     * @codeCoverageIgnore
     */
    public function finishOutput()
    {
    }

    /**
     * Returns a new game id for classes that output files.
     *
     * @param String $_outputType Output Type (PNG, Gif, Video)
     *
     * @return int New Game id
     */
    public function getNewGameId(String $_outputType): int
    {
        $fileNames = glob($this->outputDirectory . "/" . $_outputType . "/Game_*");

        if (count($fileNames) == 0) $newGameId = 1;
        else
        {
            $fileIds = array();
            foreach ($fileNames as $fileName)
            {
                $fileData = explode("_", basename($fileName));
                $fileIds[] = intval($fileData[1]);
            }

            sort($fileIds, SORT_NUMERIC);
            $newGameId = $fileIds[count($fileIds) - 1] + 1;
        }

        return $newGameId;
    }
}
