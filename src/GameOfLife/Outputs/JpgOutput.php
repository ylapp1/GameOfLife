<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Ulrichsg\Getopt;

/**
 * Saves the game steps in .jpg files.
 */
class JpgOutput extends ImageOutput
{
    /**
     * JpgOutput constructor.
     */
    public function __construct()
    {
        parent::__construct("jpg", "JPG/Game_x");
        $this->imageOutputDirectory = "JPG/Game_" . $this->getNewGameId("JPG");
    }


    /**
     * Displays a text to the user wihch tells the user that the simulation starts.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting JPG Output ...\n\n";
    }

    /**
     * Outputs one game step.
     *
     * @param Board $_board Current board
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $image = $this->imageCreator->createImage($_board);

        $fileName = $_board->gameStep() . ".jpg";
        $filePath = $this->baseOutputDirectory . "/" . $this->imageOutputDirectory . "/" . $fileName;

        imagejpeg($image, $filePath);
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