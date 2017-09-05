<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

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
class PNGOutput
{
    private $gameFolderName;
    /** @var  ImageCreator $imageCreator */
    private $imageCreator;
    /**
     * add output specific options to the option list
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions($_options)
    {
        $_options->addOptions(
            array(
                array(null, "pngOutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels for PNG outputs"),
                array(null, "pngOutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell for PNG outputs"),
                array(null, "pngOutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Color of the background for PNG outputs"),
                array(null, "pngOutputGridColor", Getopt::REQUIRED_ARGUMENT, "Color of the grid for PNG outputs")));
    }

    /**
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput($_options, $_board)
    {
        $colorSelector = new ColorSelector();

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

        $fileNames = glob(__DIR__ . "/../../../Output/PNG/Game_*");

        if (count($fileNames) == 0) $lastGameId = 0;
        else
        {
            $fileIds = array();
            foreach ($fileNames as $fileName)
            {
                $fileData = explode("_", basename($fileName));
                $fileIds[] = intval($fileData[1]);
            }

            sort($fileIds, SORT_NUMERIC);
            $lastGameId = $fileIds[count($fileIds) - 1];
        }

        // Create new folder for current game
        $this->gameFolderName = "Game_" . ($lastGameId + 1);
        mkdir(__DIR__ . "/../../../Output/PNG/" . $this->gameFolderName, 0777, true);

        // initialize ImageCreator for this PNGOutput
        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor, "/PNG/" . $this->gameFolderName);

        echo "Starting simulation ...\n\n";
    }

    /**
     * Output one game step
     *
     * @param Board $_board     Current board
     */
    public function outputBoard($_board)
    {
        $this->imageCreator->createImage($_board, "png");

        echo "\rGamestep: " . ($_board->gameStep() + 1);
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