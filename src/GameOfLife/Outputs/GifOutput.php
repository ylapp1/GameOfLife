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
        parent::__construct("GIF OUTPUT", "gif", "/tmp/Frames");
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
        $_options->addOptions(array(
                array(null, "gifOutputFrameTime", Getopt::REQUIRED_ARGUMENT, "GifOutput - Frame time of gif (in milliseconds * 10)")
            )
        );
        parent::addOptions($_options);
    }

    /**
     * Initializes the output.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     *
     * @throws \Exception The exception when one of the input colors is invalid
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);
        echo "Starting GIF Output...\n\n";

        try
        {
            $this->fileSystemHandler->createDirectory($this->baseOutputDirectory . "/Gif");
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }

        // fetch options
        $frameTime = $_options->getOption("gifOutputFrameTime");
        if ($frameTime !== null) $this->frameTime = (int)$frameTime;
        else $this->frameTime = 20;
    }

    /**
     * Creates a single Gif file for the current game step.
     *
     * @param Board $_board The board which will be output
     * @param Bool $_isFinalBoard Indicates whether the simulation ends after this output
     */
    public function outputBoard(Board $_board, Bool $_isFinalBoard)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);

        $image = $this->imageCreator->createImage($_board);

        $fileName = $_board->gameStep() . ".gif";
        $filePath = $this->imageOutputDirectory . "/" . $fileName;

        imagegif($image, $filePath);
        unset($image);

        $this->frames[] = $filePath;
    }

    /**
     * Creates an animated Gif from the gif files that were created by outputBoard().
     *
     * @param String $_simulationEndReason The reason why the simulation ended
     *
     * @throws \Exception The exception when the frames folder is empty or the gif file could not be written
     */
    public function finishOutput(String $_simulationEndReason)
    {
        parent::finishOutput($_simulationEndReason);

        unset($this->imageCreator);
        echo "\nStarting GIF creation. One moment please...";

        if (count($this->frames) == 0) throw new \Exception("No frames in frames folder found.");

        $frameDurations = array();
        for ($i = 0; $i < count($this->frames) - 1; $i++)
        {
            $frameDurations[] = $this->frameTime;
        }

        $frameDurations[] = $this->frameTime + 200;

        $gifCreator = new GifCreator();
        $gifCreator->create($this->frames, $frameDurations, 0);

        $fileName = "Game_" . $this->getNewGameId("Gif") . ".gif";

        $this->fileSystemHandler->writeFile($this->baseOutputDirectory . "/Gif/" . $fileName, $gifCreator->getGif());
        $this->fileSystemHandler->deleteDirectory($this->baseOutputDirectory . "/tmp", true);

        echo "\nGIF creation complete.\n\n";
        unset ($this->fileSystemHandler);
    }
}
