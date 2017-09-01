<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

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
    private $basePath = __DIR__ . "/../../../../Output/";
    private $backgroundColor;
    private $gridColor;
    private $cellAliveColor;
    private $cellSize;
    private $gameFolder;
    private $baseImage;
    private $cellImage;

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
        //if (! file_exists($this->basePath . "tmp")) mkdir($this->basePath . "tmp");

        // Create a base image (empty grid) on which all the other images will be based
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
        $this->baseImage = $baseImage;

        $transparentColor = new ImageColor(0,0,0);

        $red = 0;
        $green = 0;
        $blue = 0;

        while ($transparentColor == $this->backgroundColor || $transparentColor == $this->cellAliveColor)
        {
            if ($red < 255) $red++;
            elseif ($green < 255)
            {
                $green++;
                $red = 0;
            }
            elseif ($blue < 255)
            {
                $blue++;
                $green = 0;
                $red = 0;
            }

            $transparentColor = new ImageColor($red, $green, $blue);
        }


        $cellImage = imagecreatetruecolor($_cellSize, $_cellSize);
        imagefill($cellImage, 0, 0, $transparentColor->getColor($cellImage));

        $headSize = $_cellSize * 4/5;
        $padding = ($_cellSize - $headSize) / 2;

        // Head
        imagefilledellipse($cellImage, $_cellSize / 2, $_cellSize / 2, $headSize, $headSize, $this->cellAliveColor->getColor($cellImage));

        // Eyes
        imagefilledellipse($cellImage, $padding + $headSize * 1/4, $padding + $headSize / 4, $headSize / 4, $headSize / 4, $this->backgroundColor->getColor($cellImage));
        imagefilledellipse($cellImage, $padding + $headSize * 3/4, $padding + $headSize / 4, $headSize / 4, $headSize / 4, $this->backgroundColor->getColor($cellImage));

        imagesetthickness($cellImage, 5);

        // Mouth
        imagearc($cellImage, $padding + $headSize / 2, $padding + $headSize / 2, $headSize * 3/4,$headSize * 3/4, 25, 155, $this->backgroundColor->getColor($cellImage));

        imagecolortransparent($cellImage, $transparentColor->getColor($cellImage));

        $this->cellImage = $cellImage;
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
        $image = imagecreate(imagesx($this->baseImage), imagesy($this->baseImage));

        imagecopy($image, $this->baseImage, 0,0,0,0,imagesx($this->baseImage), imagesy($this->baseImage));

        // Draw the cells
        foreach ($_board->currentBoard() as $y=>$row)
        {
            foreach ($row as $x=>$cell)
            {
                imagecopymerge($image, $this->cellImage, $x * $this->cellSize, $y * $this->cellSize, 0, 0, $this->cellSize, $this->cellSize, 100);
            }
        }

        echo "\rGamestep: " . ($_board->gameStep() + 1);

        $filePath = $this->basePath;
        $fileName = $_board->gameStep();

        switch ($_imageType)
        {
            case "png":
            case "video":
                $filePath .= $this->gameFolder . "/";

                if (! file_exists($filePath)) mkdir ($filePath, 0777, true);

                $filePath .= $fileName . ".png";
                imagepng($image, $filePath);
                break;

            case "gif":
                $filePath .= "tmp/Frames/";

                if (! file_exists($filePath)) mkdir ($filePath, 0777, true);

                $filePath .= $fileName . ".gif";
                imagegif($image, $filePath);
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

        if (count(glob($tmpDirectory . "/*")) === 0 &&
            file_exists($this->basePath . "/tmp/"))
        {
            rmdir($this->basePath . "/tmp/");
        }
    }
}