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

/**
 * Class PNGOutput
 *
 * @package Output
 */
class PNGOutput
{
    private $gameFolder;

    /**
     * add output specific options to the option list
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions($_options)
    {

    }

    /**
     * Start output
     *
     * @param Getopt $_options  User inputted option list
     */
    public function startOutput($_options)
    {
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
        $this->gameFolder = __DIR__ . "/../../../Output/PNG/Game_" . ($lastGameId + 1);
        mkdir($this->gameFolder, 0777, true);

        echo "Starting simulation ...\n\n";
    }

    /**
     * Output one game step
     *
     * @param Board $_board     Current board
     */
    public function outputBoard($_board)
    {
        $imageCreator = new ImageCreator($_board, $this->gameFolder);
        $imageCreator->createImage($_board, "png");

        echo "\rGamestep: " . ($_board->gameStep() + 1);
    }

    /**
     * Finish output (write to file)
     */
    public function finishOutput()
    {
        echo "\n\nSimulation finished. All cells are dead or a repeating pattern was detected.";
    }
}