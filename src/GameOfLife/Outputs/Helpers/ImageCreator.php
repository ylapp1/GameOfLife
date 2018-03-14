<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\Helpers;

use GameOfLife\Board;
use GameOfLife\Field;
use Utils\FileSystemHandler;

/**
 * Creates an image of a board and returns its output path.
 *
 * @package Output
 */
class ImageCreator
{
    private $outputPath;
    private $cellSize;
    private $baseImage;
    private $cellImage;
    private $fileSystemHandler;

    /**
     * Returns the cell size of the image.
     *
     * @return int  The cell size of the image
     */
    public function cellSize(): int
    {
        return $this->cellSize;
    }

    /**
     * Sets the cell size of the image.
     *
     * @param int $_cellSize    The cell size of the image
     */
    public function setCellSize(int $_cellSize)
    {
        $this->cellSize = $_cellSize;
    }

    /**
     * Returns the base image for all images (an empty grid with the colors that were defined in the ImageCreator).
     *
     * @return resource     The base image for all images
     */
    public function baseImage()
    {
        return $this->baseImage;
    }

    /**
     * Sets the base image for all images.
     *
     * @param resource $_baseImage  The base image for all images
     */
    public function setBaseImage($_baseImage)
    {
        $this->baseImage = $_baseImage;
    }

    /**
     * Returns the cell image for all images (a smiley with the colors that were defined in the ImageCreator).
     *
     * @return resource     The cell image for all images
     */
    public function cellImage()
    {
        return $this->cellImage;
    }

    /**
     * Sets the cell image for all images.
     *
     * @param resource $_cellImage  The cell image for all images
     */
    public function setCellImage($_cellImage)
    {
        $this->cellImage = $_cellImage;
    }

    /**
     * Returns the filesystem handler of this ImageCreator.
     *
     * @return FileSystemHandler    The filesystem handler
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the filesystem handler of this ImageCreator.
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
     */
    public function __construct(int $_boardHeight, int $_boardWidth, int $_cellSize, ImageColor $_cellAliveColor,
                                ImageColor $_backgroundColor, ImageColor $_gridColor)
    {
        $this->cellSize = $_cellSize;

        // Generate base images
        $this->baseImage = $this->initializeBaseImage($_boardWidth, $_boardHeight, $_backgroundColor, $_gridColor);
        $this->cellImage = $this->initializeCellImage($_backgroundColor, $_cellAliveColor);

        $this->fileSystemHandler = new FileSystemHandler();
    }

    /**
     * Initializes and returns the background image of each image (an empty grid).
     *
     * Requires the class attribute $cellSize to be set
     *
     * @param int $_width Board width
     * @param int $_height Board height
     * @param ImageColor $_backgroundColor Color of the background
     * @param ImageColor $_gridColor Color of the grid
     *
     * @return resource Base Image
     */
    private function initializeBaseImage(int $_width, int $_height, ImageColor $_backgroundColor, ImageColor $_gridColor)
    {
        $baseImage = imagecreate($_width * $this->cellSize, $_height * $this->cellSize);
        imagefill($baseImage, 0, 0, $_backgroundColor->getColor($baseImage));

        // draw grid
        imagesetthickness($baseImage, 1);

        // Vertical lines
        for ($x = 0; $x < $_width * $this->cellSize; $x += $this->cellSize)
        {
            imageline($baseImage, $x, 0, $x, imagesy($baseImage), $_gridColor->getColor($baseImage));
        }

        // Horizontal lines
        for ($y = 0; $y < $_height * $this->cellSize; $y += $this->cellSize)
        {
            imageline($baseImage, 0, $y, imagesx($baseImage), $y, $_gridColor->getColor($baseImage));
        }

        imagesetthickness($baseImage, 1);

        return $baseImage;
    }

    /**
     * Initializes and returns the image for living cells.
     *
     * Requires the class attribute $cellSize to be set
     *
     * @param ImageColor $_backgroundColor Color of the background
     * @param ImageColor $_cellAliveColor Color of living cells
     *
     * @return resource Cell image
     */
    private function initializeCellImage(ImageColor $_backgroundColor, ImageColor $_cellAliveColor)
    {
        // Generate a color that is unequal to the background as well as the cell color
        // The color is used to make the space around the smiley transparent
        $transparentColorRed = 0;
        while ($transparentColorRed == $_backgroundColor->red() || $transparentColorRed == $_cellAliveColor->red())
        {
            $transparentColorRed++;
        }

        $transparentColor = new ImageColor($transparentColorRed, 0, 0);

        // Create Smiley Image for living cells
        $cellImage = imagecreatetruecolor($this->cellSize, $this->cellSize);
        imagefill($cellImage, 0, 0, $transparentColor->getColor($cellImage));

        $headSize = $this->cellSize * 4/5;
        $padding = ($this->cellSize - $headSize) / 2;

        // Head
        imagefilledellipse($cellImage, $this->cellSize / 2, $this->cellSize / 2, $headSize, $headSize, $_cellAliveColor->getColor($cellImage));
        // Eyes
        imagefilledellipse($cellImage, $padding + $headSize * 1/4, $padding + $headSize / 4, $headSize / 4, $headSize / 4, $_backgroundColor->getColor($cellImage));
        imagefilledellipse($cellImage, $padding + $headSize * 3/4, $padding + $headSize / 4, $headSize / 4, $headSize / 4, $_backgroundColor->getColor($cellImage));
        // Mouth
        imagesetthickness($cellImage, 5);
        imagearc($cellImage, $padding + $headSize / 2, $padding + $headSize / 2, $headSize * 3/4,$headSize * 3/4, 25, 155, $_backgroundColor->getColor($cellImage));

        imagecolortransparent($cellImage, $transparentColor->getColor($cellImage));

        return $cellImage;
    }


    /**
     * Creates and returns an image of the current board.
     *
     * @param Board $_board The current board
     *
     * @return resource The image
     */
    public function createImage (Board $_board)
    {
        $image = imagecreate(imagesx($this->baseImage), imagesy($this->baseImage));

        imagecopy($image, $this->baseImage, 0,0,0,0,imagesx($this->baseImage), imagesy($this->baseImage));

        // Draw the cells
        foreach ($_board->fields() as $y=> $row)
        {
            foreach ($row as $x=>$field)
            {
                if ($field instanceof Field)
                {
                    if ($field->isAlive())
                    {
                        imagecopymerge($image, $this->cellImage, $x * $this->cellSize, $y * $this->cellSize, 0, 0, $this->cellSize, $this->cellSize, 100);
                    }
                }
            }
        }

        return $image;
    }
}
