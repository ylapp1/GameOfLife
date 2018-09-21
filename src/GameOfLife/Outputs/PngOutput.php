<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use Simulator\Board;
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
     * @param int $_gameStep The current game step
     */
    public function outputBoard(Board $_board, int $_gameStep)
    {
        echo "\rGamestep: " . $_gameStep;
	    $image = $this->boardRenderer->renderBoard($_board);

        $fileName = $_gameStep . ".png";
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
