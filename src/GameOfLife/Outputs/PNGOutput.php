<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use Utils\FileSystemHandler;
use Ulrichsg\Getopt;
Use GameOfLife\Board;
use Output\Helpers\ImageCreator;
use Output\Helpers\ColorSelector;
use Output\Helpers\ImageColor;


/**
 * Class PNGOutput
 *
 * @package Output
 */
class PNGOutput extends BaseOutput
{
    /** @var  ImageCreator $imageCreator */
    private $imageCreator;
    /** @var FileSystemHandler */
    private $fileSystemHandler;
    /**
     * add output specific options to the option list
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, "pngOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for PNG outputs"),
                array(null, "pngOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for PNG outputs"),
                array(null, "pngOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Color of the background for PNG outputs"),
                array(null, "pngOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Color of the grid for PNG outputs")));
    }

    /**
     * Fetches the options and creates the necessary directories if they do not exist yet
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        $colorSelector = new ColorSelector();
        $this->fileSystemHandler = new FileSystemHandler();

        // fetch options
        if ($_options->getOption("pngOutputSize")) $cellSize = intval($_options->getOption("pngOutputSize"));
        else $cellSize = 100;

        $inputCellColor = $_options->getOption("pngOutputCellColor");
        if ($inputCellColor != false) $cellColor = $colorSelector->getColor($inputCellColor);
        else $cellColor = new ImageColor(0,0,0);

        $inputBackgroundColor = $_options->getoption("pngOutputBackgroundColor");
        if ($inputBackgroundColor != false) $backgroundColor = $colorSelector->getColor($inputBackgroundColor);
        else $backgroundColor = new ImageColor(255, 255,255);

        $inputGridColor = $_options->getoption("pngOutputGridColor");
        if ($inputGridColor != false) $gridColor = $colorSelector->getColor($inputGridColor);
        else $gridColor = new ImageColor(0, 0, 0);

        // Create new folder for current game
        $gameFolderName = "/PNG/Game_" . $this->getNewGameId("PNG");
        $this->fileSystemHandler->createDirectory($this->outputDirectory . $gameFolderName);

        // initialize ImageCreator for this PNGOutput
        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor, $gameFolderName);

        echo "Starting simulation ...\n\n";
    }

    /**
     * Output one game step
     *
     * @param Board $_board     Current board
     */
    public function outputBoard(Board $_board)
    {
        echo "\rGamestep: " . ($_board->gameStep() + 1);
        $this->imageCreator->createImage($_board, "png");
    }

    /**
     * Finish output (write to file)
     */
    public function finishOutput()
    {
        unset($this->imageCreator);
        echo "\n\nSimulation finished. All cells are dead or a repeating pattern was detected.";
    }
}