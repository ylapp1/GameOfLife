<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * Saves the boards as .png files.
 *
 * @package Output
 */
class PngOutput extends ImageOutput
{
    /**
     * PngOutput constructor.
     */
    public function __construct()
    {
        $outputDirectory = $this->outputDirectory . "/PNG/Game_" . $this->getNewGameId("PNG");
        parent::__construct("png", $outputDirectory);
    }


    /**
     * Displays a text to the user that the simulation now starts.
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting PNG Output ...\n\n";
    }

    /**
     * Outputs one game step.
     *
     * @param Board $_board     Current board
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->imageCreator->createImage($_board, "png");
    }

    /**
     * Displays a text which tells the user that the simulation is finished.
     */
    public function finishOutput()
    {
        unset($this->imageCreator);
        echo "\n\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n";
    }
}