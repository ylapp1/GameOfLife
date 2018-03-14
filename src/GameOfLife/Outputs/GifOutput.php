<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Tim Schreindl <tim.schreindl@cn-consult.eu>
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use GifCreator\GifCreator;
use Ulrichsg\Getopt;

/**
 * Saves the boards in an animated gif file.
 */
class GifOutput extends ImageOutput
{
    /**
     * File paths of the gif frames
     *
     * @var array $frames
     */
    private $frames = array();

    /**
     * Time for which a frame is displayed
     *
     * @var int $frameTime
     */
    private $frameTime;


    /**
     * GifOutput constructor.
     */
    public function __construct()
    {
        $outputDirectory = $this->baseOutputDirectory . "/tmp/Frames";
        parent::__construct("gif", $outputDirectory);
    }


    /**
     * Returns the frame save paths.
     *
     * @return array Frame save paths
     */
    public function frames(): array
    {
        return $this->frames;
    }

    /**
     * Sets the frame save paths.
     *
     * @param array $_frames Frame save paths
     */
    public function setFrames(array $_frames)
    {
        $this->frames = $_frames;
    }

    /**
     * Returns the time per frame.
     *
     * @return int Time per frame
     */
    public function frameTime(): int
    {
        return $this->frameTime;
    }

    /**
     * Sets the time per frame.
     *
     * @param int $_frameTime Time per frame
     */
    public function setFrameTime(int $_frameTime)
    {
        $this->frameTime = $_frameTime;
    }


    /**
     * Adds GIFOutputs specific options to a Getopt object.
     *
     * @param Getopt $_options The option list to which the options are added
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
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting GIF Output...\n\n";

        $this->fileSystemHandler->createDirectory($this->baseOutputDirectory . "/Gif");

        // fetch options
        $frameTime = $_options->getOption("gifOutputFrameTime");
        if ($frameTime !== null) $this->frameTime = (int)$frameTime;
        else $this->frameTime = 20;
    }

    /**
     * Creates a single Gif file for the current game step.
     *
     * @param Board $_board The board which will be output
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->frames[] = $this->imageCreator->createImage($_board, "gif");
    }

    /**
     * Creates an animated Gif from the gif files that were created by outputBoard().
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     */
    public function finishOutput(String $_simulationEndReason)
    {
        parent::finishOutput($_simulationEndReason);
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

        $gifCreator = new GifCreator();
        $gifCreator->create($this->frames, $frameDurations, 0);

        $fileName = "Game_" . $this->getNewGameId("Gif") . ".gif";
        $filePath = $this->baseOutputDirectory . "Gif/" . $fileName;

        file_put_contents($filePath, $gifCreator->getGif());

        if (! file_exists($filePath))
        {
            echo "An error occurred during the gif creation. Stopping...";
            return;
        }

        unset($this->imageCreator);
        $this->fileSystemHandler->deleteDirectory($this->baseOutputDirectory . "/tmp", true);

        echo "\nGIF creation complete.\n\n";
    }
}
