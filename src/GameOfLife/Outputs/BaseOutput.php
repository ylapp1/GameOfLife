<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use Ulrichsg\Getopt;
use GameOfLife\Board;

/**
 * Class BaseOutput
 *
 * @package Output
 */
class BaseOutput
{
    protected $outputDirectory = __DIR__ . "/../../../Output/";

    /**
     * @return string
     */
    public function outputDirectory(): string
    {
        return $this->outputDirectory;
    }

    /**
     * @param string $outputDirectory
     */
    public function setOutputDirectory(string $outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
    }

    /**
     * add output specific options to the option list
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions(Getopt $_options)
    {
    }

    /**
     * Start output
     *
     * @codeCoverageIgnore
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
    }

    /**
     * Output one game step
     *
     * @codeCoverageIgnore
     *
     * @param Board $_board     Current board
     */
    public function outputBoard(Board $_board)
    {
    }

    /**
     * Finish output (Display that simulation is finished, write files and delete temporary files)
     *
     * @codeCoverageIgnore
     */
    public function finishOutput()
    {
    }

    /**
     * Returns a new game id
     *
     * @param string $outputType    Output Type (PNG, Gif, Video)
     * @return int      New Game id
     */
    public function getNewGameId(string $outputType)
    {
        $fileNames = glob($this->outputDirectory . "/" . $outputType . "/Game_*");

        if (count($fileNames) == 0) $newGameId = 0;
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