<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;

/**
 * Class ImageCreator
 *
 * Create an image of a board and return it
 *
 * @package Output
 */
class ImageCreator
{
    private $basePath = __DIR__ . "/../../../Output/";
    private $backgroundColor;
    private $gridColor;
    private $cellAliveColor;
    private $cellSize;
    private $gameFolder;

    /**
     * ImageCreator constructor.
     * @param $_boardHeight
     * @param $_boardWidth
     * @param Integer $_cellSize            Width and Height of a single cell
     * @param ImageColor $_cellAliveColor   Cell color of the images
     * @param ImageColor $_backgroundColor  Background Color of the images
     * @param ImageColor $_gridColor        Grid color of the images
     * @param String $_gameFolder           The complete game folder path of a png output
     */
    public function __construct($_boardHeight, $_boardWidth, $_cellSize, $_cellAliveColor, $_backgroundColor, $_gridColor, $_gameFolder = null)
    {
        // Create temporary directory if it doesn't exist
        if (! file_exists($this->basePath . "tmp")) mkdir($this->basePath . "tmp");

        // Create a base Image on which all the other images will be based
        $this->cellSize = $_cellSize;
        $this->gameFolder = $_gameFolder;

        $baseImage = imagecreate($_boardWidth * $this->cellSize, $_boardHeight * $this->cellSize);

        // set colors
        $this->backgroundColor = $_backgroundColor;
        $this->gridColor = $_gridColor;
        $this->cellAliveColor = $_cellAliveColor;
        $this->gridColor = $_gridColor;

        imagefill($baseImage, 0, 0, $this->backgroundColor->getColor($baseImage));

        // draw grid
        imagesetthickness($baseImage, 1);

        for ($x = 0; $x < $_boardWidth * $this->cellSize; $x += $this->cellSize)
        {
            imageline($baseImage, $x, 0, $x, imagesy($baseImage), $this->gridColor->getColor($baseImage));
        }

        for ($y = 0; $y < $_boardHeight * $this->cellSize; $y += $this->cellSize)
        {
            imageline($baseImage, 0, $y, imagesx($baseImage), $y, $this->gridColor->getColor($baseImage));
        }

        imagesetthickness($baseImage, 1);

        imagepng($baseImage, $this->basePath . "/tmp/base.png");
    }

    /**
     * Creates and returns an image of the current board
     *
     * @param Board $_board     Current board
     * @param String $_imageType            Type of Image that shall be returned
     * @return String                       Path to image
     */
    public function createImage ($_board, $_imageType)
    {
        $image = imagecreatefrompng($this->basePath . "/tmp/base.png");

        // Draw the cells
        for ($y = 0; $y < imagesy($image); $y += $this->cellSize)
        {
            for ($x = 0; $x < imagesx($image); $x += $this->cellSize)
            {
                if ($_board->getField($x / $this->cellSize, $y / $this->cellSize) == true)
                {
                  imagefilledellipse($image, $x + $this->cellSize/2, $y + $this->cellSize/2, $this->cellSize - 5, $this->cellSize - 5, $this->cellAliveColor->getColor($image));
                }
            }
        }

        echo "\rGamestep: " . ($_board->gameStep() + 1);

        $filePath = $this->basePath;
        $fileName = $_board->gameStep();

        switch ($_imageType)
        {
            case "png":
                $filePath .= $this->gameFolder . "/";

                if (! file_exists($filePath)) mkdir ($filePath, 0777, true);

                $filePath .= $fileName . ".png";
                imagepng($image, $filePath);
                break;

            case "gif":
            case "video":
                $filePath .= "tmp/Frames/";

                if (! file_exists($filePath)) mkdir ($filePath, 0777, true);

                $filePath .= $fileName . ".png";
                imagepng($image, $filePath);
                break;
            default:
                echo "Error: Invalid image type specified!\n";
        }

        imagedestroy($image);

        return $filePath;
    }

    /**
     * Class destructor
     *
     * Deletes remaining tmp files
     */
    public function __destruct()
    {
        $tmpDirectory = $this->basePath . "/tmp";
        unlink($tmpDirectory  . "/base.png");

        if (count(glob($tmpDirectory . "/*")) === 0) rmdir($this->basePath . "/tmp/");
        else echo count(glob($tmpDirectory . "/*")) . " files left in tmp directory";
    }
}