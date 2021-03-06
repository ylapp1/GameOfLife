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
 * Saves the boards as .png files.
 */
class PngOutput extends ImageOutput
{
    /**
     * PngOutput constructor.
     */
    public function __construct()
    {
        parent::__construct("PNG OUTPUT", "png", "/PNG");
        $this->imageOutputDirectory .=  "/Game_" . $this->getNewGameId("PNG");
    }


    /**
     * Displays a text to the user wihch tells the user that the simulation starts.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     *
     * @throws \Exception The exception when one of the input colors is invalid
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting PNG Output ...\n\n";
    }

    /**
     * Outputs one game step.
     *
     * @param Board $_board Current board
     * @param Bool $_isFinalBoard Indicates whether the simulation ends after this output
     */
    public function outputBoard(Board $_board, Bool $_isFinalBoard)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $image = $this->imageCreator->createImage($_board);

        $fileName = $_board->gameStep() . ".png";
        $filePath = $this->imageOutputDirectory . "/" . $fileName;

        imagepng($image, $filePath);
        unset($image);
    }

    /**
     * Displays a text which tells the user that the simulation is finished.
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     */
    public function finishOutput(String $_simulationEndReason)
    {
        parent::finishOutput($_simulationEndReason);
        unset($this->imageCreator);
    }
}
