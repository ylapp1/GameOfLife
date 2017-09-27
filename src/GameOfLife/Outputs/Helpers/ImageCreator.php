<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

use GameOfLife\Board;
use Utils\FileSystemHandler;

/**
 * Creates an image of a board and returns its output path
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
    private $fileSystemHandler;

    /**
     * Returns the base output directory
     *
     * @return string   The base output directory
     */
    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * Sets the base output directory
     *
     * @param string $_basePath     The base output directory
     */
    public function setBasePath(string $_basePath)
    {
        $this->basePath = $_basePath;
    }

    /**
     * Returns the background color of the image
     *
     * @return ImageColor   Background color of the image
     */
    public function backgroundColor(): ImageColor
    {
        return $this->backgroundColor;
    }

    /**
     * Sets the background color of the image
     *
     * @param ImageColor $_backgroundColor      Background color of the image
     */
    public function setBackgroundColor(ImageColor $_backgroundColor)
    {
        $this->backgroundColor = $_backgroundColor;
    }

    /**
     * Returns the grid color of the image
     *
     * @return ImageColor   Grid color of the image
     */
    public function gridColor(): ImageColor
    {
        return $this->gridColor;
    }

    /**
     * Sets the grid color of the image
     *
     * @param ImageColor $_gridColor    Grid color of the image
     */
    public function setGridColor(ImageColor $_gridColor)
    {
        $this->gridColor = $_gridColor;
    }

    /**
     * Returns the cell color of the image
     *
     * @return ImageColor   Cell color of the image
     */
    public function cellAliveColor(): ImageColor
    {
        return $this->cellAliveColor;
    }

    /**
     * Sets the cell color of the image
     *
     * @param ImageColor $_cellAliveColor   Cell color of the image
     */
    public function setCellAliveColor(ImageColor $_cellAliveColor)
    {
        $this->cellAliveColor = $_cellAliveColor;
    }

    /**
     * Returns the cell size of the image
     *
     * @return int  The cell size of the image
     */
    public function cellSize(): int
    {
        return $this->cellSize;
    }

    /**
     * Sets the cell size of the image
     *
     * @param int $_cellSize    The cell size of the image
     */
    public function setCellSize(int $_cellSize)
    {
        $this->cellSize = $_cellSize;
    }

    /**
     * Returns the game folder in which the images will be saved
     *
     * @return string   The game folder in which the images will be saved
     */
    public function gameFolder(): string
    {
        return $this->gameFolder;
    }

    /**
     * Sets the game folder in which the images will be saved
     *
     * @param string $_gameFolder   The game folder in which the images will be saved
     */
    public function setGameFolder(string $_gameFolder)
    {
        $this->gameFolder = $_gameFolder;
    }

    /**
     * Returns the base image for all images (an empty grid with the colors that were defined in the ImageCreator)
     *
     * @return resource     The base image for all images
     */
    public function baseImage()
    {
        return $this->baseImage;
    }

    /**
     * Sets the base image for all images
     *
     * @param resource $_baseImage  The base image for all images
     */
    public function setBaseImage($_baseImage)
    {
        $this->baseImage = $_baseImage;
    }

    /**
     * Returns the cell image for all images (a smiley with the colors that were defined in the ImageCreator)
     *
     * @return resource     The cell image for all images
     */
    public function cellImage()
    {
        return $this->cellImage;
    }

    /**
     * Sets the cell image for all images
     *
     * @param resource $_cellImage  The cell image for all images
     */
    public function setCellImage($_cellImage)
    {
        $this->cellImage = $_cellImage;
    }

    /**
     * Returns the filesystem handler of this ImageCreator
     *
     * @return FileSystemHandler    The filesystem handler
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the filesystem handler of this ImageCreator
     *
     * @param FileSystemHandler $_fileSystemHandler     The filesystem handler
     */
    public function setFileSystemHandler(FileSystemHandler $_fileSystemHandler)
    {
        $this->fileSystemHandler = $_fileSystemHandler;
    }


    /**
     * ImageCreator constructor.
     *
     * @param int $_boardHeight             The height of the board
     * @param int $_boardWidth              The width of the board
     * @param int $_cellSize                Width and Height of a single cell
     * @param ImageColor $_cellAliveColor   Cell color of the images
     * @param ImageColor $_backgroundColor  Background Color of the images
     * @param ImageColor $_gridColor        Grid color of the images
     * @param string $_gameFolder           The complete game folder path of a png output
     */
    public function __construct(int $_boardHeight, int $_boardWidth, int $_cellSize, ImageColor $_cellAliveColor,
                                ImageColor $_backgroundColor, ImageColor $_gridColor, string $_gameFolder = null)
    {
        // Create a base image (empty grid) on which all the other images will be based
        $this->cellSize = $_cellSize;
        $this->gameFolder = $_gameFolder;

        $baseImage = imagecreate($_boardWidth * $this->cellSize, $_boardHeight * $this->cellSize);

        // set colors
        $this->backgroundColor = $_backgroundColor;
        $this->gridColor = $_gridColor;
        $this->cellAliveColor = $_cellAliveColor;

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

        $red = 0;
        while ($red == $this->backgroundColor->red() || $red == $this->cellAliveColor->red())
        {
                $red++;
        }

        $transparentColor = new ImageColor($red, 0, 0);

        // Create Smiley Image for living cells
        $cellImage = imagecreatetruecolor($_cellSize, $_cellSize);
        imagefill($cellImage, 0, 0, $transparentColor->getColor($cellImage));

        $headSize = $_cellSize * 4/5;
        $padding = ($_cellSize - $headSize) / 2;

        // Head
        imagefilledellipse($cellImage, $_cellSize / 2, $_cellSize / 2, $headSize, $headSize, $this->cellAliveColor->getColor($cellImage));
        // Eyes
        imagefilledellipse($cellImage, $padding + $headSize * 1/4, $padding + $headSize / 4, $headSize / 4, $headSize / 4, $this->backgroundColor->getColor($cellImage));
        imagefilledellipse($cellImage, $padding + $headSize * 3/4, $padding + $headSize / 4, $headSize / 4, $headSize / 4, $this->backgroundColor->getColor($cellImage));
        // Mouth
        imagesetthickness($cellImage, 5);
        imagearc($cellImage, $padding + $headSize / 2, $padding + $headSize / 2, $headSize * 3/4,$headSize * 3/4, 25, 155, $this->backgroundColor->getColor($cellImage));

        imagecolortransparent($cellImage, $transparentColor->getColor($cellImage));

        $this->cellImage = $cellImage;

        $this->fileSystemHandler = new FileSystemHandler();
    }

    /**
     * Creates and returns an image of the current board
     *
     * @param Board $_board                 Current board
     * @param string $_imageType            Type of Image that shall be returned
     *
     * @return string                       Path to image
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
                $filePath .= $this->gameFolder;
                $this->fileSystemHandler->createDirectory($filePath);

                $filePath .= "/" . $fileName . ".png";
                imagepng($image, $filePath);
                break;

            case "gif":
                $filePath .= $this->gameFolder;
                $this->fileSystemHandler->createDirectory($filePath);

                $filePath .= "/" . $fileName . ".gif";
                imagegif($image, $filePath);
                break;
            default:
                echo "Error: Invalid image type specified!\n";
        }

        imagedestroy($image);

        return $filePath;
    }
}