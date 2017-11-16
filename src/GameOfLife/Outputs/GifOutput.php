<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use GIFEncoder\GIFEncoder;
use Ulrichsg\Getopt;

/**
 * Saves the boards in an animated gif file.
 *
 * @package Output
 */
class GifOutput extends ImageOutput
{
    private $frames = array();
    private $frameTime;


    /**
     * GifOutput constructor.
     */
    public function __construct()
    {
        $outputDirectory = $this->outputDirectory . "/tmp/Frames";
        parent::__construct("gif", $outputDirectory);
    }


    /**
     * Returns the frame save paths.
     *
     * @return array    Frame save paths
     */
    public function frames(): array
    {
        return $this->frames;
    }

    /**
     * Sets the frame save paths.
     *
     * @param array $_frames    Frame save paths
     */
    public function setFrames(array $_frames)
    {
        $this->frames = $_frames;
    }

    /**
     * Returns the time per frame.
     *
     * @return int      Time per frame
     */
    public function frameTime(): int
    {
        return $this->frameTime;
    }

    /**
     * Sets the time per frame.
     *
     * @param int $_frameTime   Time per frame
     */
    public function setFrameTime(int $_frameTime)
    {
        $this->frameTime = $_frameTime;
    }


    /**
     * Adds GIFOutputs specific options to a Getopt object.
     *
     * @param Getopt $_options      The option list to which the options are added
     */
    public function addOptions(Getopt $_options)
    {
        parent::addOptions($_options);
        $_options->addOptions(array(
                                array(null, "gifOutputFrameTime", Getopt::REQUIRED_ARGUMENT, "Frame time of gif (in milliseconds * 10)")
                              )
        );
    }

    /**
     * Initializes the output.
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting GIF Output...\n\n";

        $this->fileSystemHandler->createDirectory($this->outputDirectory . "/Gif");

        // fetch options
        $frameTime = $_options->getOption("gifOutputFrameTime");
        if ($frameTime !== null) $this->frameTime = (int)$frameTime;
        else $this->frameTime = 20;
    }

    /**
     * Creates a single Gif file for the current game step.
     *
     * @param Board $_board     The board which will be output
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->frames[] = $this->imageCreator->createImage($_board, "gif");
    }

    /**
     * Creates an animated Gif from the gif files that were created by outputBoard().
     */
    public function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead, a repeating pattern was detected or maxSteps was reached.\n\n";
        echo "\nStarting GIF creation. One moment please...";

        if (count($this->frames) == 0)
        {
            echo "Error: No frames in frames folder found!\n";
            return;
        }

        $frameDurations = array();

        for ($i = 0; $i < count($this->frames) - 1; $i++)
        {
            $frameDurations[] = $this->frameTime;
        }

        $frameDurations[] = $this->frameTime + 200;

        $gif = new GIFEncoder($this->frames, $frameDurations, 0, 2, 1, 0, 0, "url");
        $fileName = "Game_" . $this->getNewGameId("Gif") . ".gif";

        if (fwrite(fopen($this->outputDirectory . "Gif/" . $fileName, "wb"), $gif->GetAnimation()) == false)
        {
            echo "An error occurred during the gif creation. Stopping...";
            return;
        }

        unset($this->imageCreator);
        $this->fileSystemHandler->deleteDirectory($this->outputDirectory . "/tmp", true);

        echo "\nGIF creation complete.";
    }
}