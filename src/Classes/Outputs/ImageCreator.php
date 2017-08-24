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
    private $baseImage;
    private $basePath = __DIR__ . "/../../../Output/";
    private $backgroundColor;
    private $gridColor;
    private $cellAliveColor;
    private $cellSize;
    private $gameFolder;

    /**
     * ImageCreator constructor.
     *
     * @param Board $_board                 The board which will be printed with create image
     * @param String $_gameFolder           The complete game folder path of a png output
     * @param Integer $_cellSize            Width and Height of a single cell
     * @param ImageColor $_backgroundColor  Background Color of the images
     * @param ImageColor $_gridColor        Grid color of the images
     * @param ImageColor $_cellAliveColor   Cell color of the images
     */
    public function __construct($_board, $_cellSize, $_cellAliveColor, $_backgroundColor, $_gameFolder = null, $_gridColor = null)
    {
        // Check values of parameters and use default values if they are not set
        if ($_gridColor == null) $_gridColor = new ImageColor(0,0,0);

        // Create a base Image on which all the other images will be based on
        $this->cellSize = $_cellSize;
        $this->baseImage = imagecreate($_board->width() * $this->cellSize, $_board->height() * $this->cellSize);
        $this->gameFolder = $_gameFolder;

        // set colors
        $this->backgroundColor = $_backgroundColor->getColor($this->baseImage);
        $this->gridColor = $_gridColor->getColor($this->baseImage);
        $this->cellAliveColor = $_cellAliveColor->getColor($this->baseImage);

        // Create directories if they don't exist
        if (! file_exists($this->basePath . "PNG")) mkdir($this->basePath . "PNG", 0777, true);
        if (! file_exists($this->basePath . "Gif")) mkdir($this->basePath . "Gif", 0777, true);

        imagefill($this->baseImage, 0, 0, $this->backgroundColor);

        // draw grid
        imagesetthickness($this->baseImage, 1);

        for ($x = 0; $x < $_board->width() * $this->cellSize; $x += $this->cellSize)
        {
            imageline($this->baseImage, $x, 0, $x, imagesy($this->baseImage), $this->gridColor);
        }

        for ($y = 0; $y < $_board->height() * $this->cellSize; $y += $this->cellSize)
        {
            imageline($this->baseImage, 0, $y, imagesx($this->baseImage), $y, $this->gridColor);
        }

        imagesetthickness($this->baseImage, 1);
    }

    /**
     * Creates and returns an image of the current board
     *
     * @param \GameOfLife\Board $_board     Current board
     * @param String $_imageType            Type of Image that shall be returned
     * @return String                       Path to image
     */
    public function createImage ($_board, $_imageType)
    {
        $image = $this->baseImage;

        // Draw the cells
        for ($y = 0; $y < imagesy($image); $y += $this->cellSize)
        {
            for ($x = 0; $x < imagesx($image); $x += $this->cellSize)
            {
                if ($_board->getField($x / $this->cellSize, $y / $this->cellSize) == true)
                {
                  imagefilledellipse($image, $x + $this->cellSize/2, $y + $this->cellSize/2, $this->cellSize - 5, $this->cellSize - 5, $this->cellAliveColor);
                }
            }
        }

        echo "\rGamestep: " . ($_board->gameStep() + 1);

        $filePath = $this->basePath;
        $fileName = $_board->gameStep();

        switch ($_imageType)
        {
            case "png":
                $filePath = $this->gameFolder . "/" . $fileName . ".png";
                imagepng($image, $filePath);
                break;
            case "gif":
                $filePath .= "Gif/Frames/" . $fileName . ".gif";
                imagegif($image, $filePath);
                break;
            default:
                echo "Error: Invalid image type specified!\n";
        }

        imagedestroy($image);

        return $filePath;
    }
}