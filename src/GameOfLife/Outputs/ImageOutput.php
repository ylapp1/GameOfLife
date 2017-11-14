<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Output\Helpers\ColorSelector;
use Output\Helpers\ImageColor;
use Output\Helpers\ImageCreator;
use Ulrichsg\Getopt;
use Utils\FileSystemHandler;

/**
 * Parent class for all classes that output images or process temporary output images.
 *
 * @package Output
 */
class ImageOutput extends BaseOutput
{
    /** @var FileSystemHandler */
    protected $fileSystemHandler;
    /** @var ImageCreator $imageCreator */
    protected $imageCreator;
    private $imageOutputDirectory;
    private $optionPrefix;

    /**
     * ImageOutput constructor.
     *
     * @param string $_optionPrefix          Prefix for all options of this output
     * @param string $_imageOutputDirectory  The directory in which the images will be saved
     */
    public function __construct(string $_optionPrefix, string $_imageOutputDirectory)
    {
        $this->optionPrefix = $_optionPrefix;
        $this->fileSystemHandler = new FileSystemHandler();
        $this->imageOutputDirectory = $_imageOutputDirectory;
    }


    /**
     * Returns the filesystem handler of this output.
     *
     * @return FileSystemHandler    Filesystem handler
     */
    public function fileSystemHandler(): FileSystemHandler
    {
        return $this->fileSystemHandler;
    }

    /**
     * Sets the filesystem handler of this output.
     *
     * @param FileSystemHandler $_fileSystemHandler     Filesystem handler
     */
    public function setFileSystemHandler(FileSystemHandler $_fileSystemHandler)
    {
        $this->fileSystemHandler = $_fileSystemHandler;
    }

    /**
     * Returns the image creator of this output.
     *
     * @return ImageCreator     The image creator
     */
    public function imageCreator(): ImageCreator
    {
        return $this->imageCreator;
    }

    /**
     * Sets the image creator of this output.
     *
     * @param ImageCreator $_imageCreator   The image creator
     */
    public function setImageCreator(ImageCreator $_imageCreator)
    {
        $this->imageCreator = $_imageCreator;
    }

    /**
     * Returns the image output directory.
     *
     * @return string   Image output directory
     */
    public function imageOutputDirectory(): string
    {
        return $this->imageOutputDirectory;
    }

    /**
     * Sets the image output directory.
     *
     * @param string $_imageOutputDirectory     Image output directory
     */
    public function setImageOutputDirectory(string $_imageOutputDirectory)
    {
        $this->imageOutputDirectory = $_imageOutputDirectory;
    }

    /**
     * Returns the option prefix of this image output.
     *
     * @return string   Option prefix
     */
    public function optionPrefix(): string
    {
        return $this->optionPrefix;
    }

    /**
     * Sets the option prefix for this image output.
     *
     * @param string $_optionPrefix     Option prefix
     */
    public function setOptionPrefix(string $_optionPrefix)
    {
        $this->optionPrefix = $_optionPrefix;
    }


    /**
     * Adds the image options to the option list.
     *
     * @param Getopt $_options     Current option list
     */
    public function addOptions(Getopt $_options)
    {
        $_options->addOptions(
            array(
                array(null, $this->optionPrefix . "OutputSize", Getopt::REQUIRED_ARGUMENT, "Size of a cell in pixels"),
                array(null, $this->optionPrefix . "OutputCellColor", Getopt::REQUIRED_ARGUMENT, "Color of a cell"),
                array(null, $this->optionPrefix . "OutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, "Background color"),
                array(null, $this->optionPrefix . "OutputGridColor", Getopt::REQUIRED_ARGUMENT, "Grid color")
            )
        );
    }

    /**
     * Initializes the image creator.
     *
     * @param Getopt $_options  User inputted option list
     * @param Board $_board     Initial board
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        $this->fileSystemHandler->createDirectory($this->imageOutputDirectory);

        $colorSelector = new ColorSelector();

        // fetch options
        $cellSize = $_options->getOption($this->optionPrefix . "OutputSize");
        if ($cellSize !== null) $cellSize = (int)$cellSize;
        else $cellSize = 100;

        $cellColor = $_options->getOption($this->optionPrefix . "OutputCellColor");
        if ($cellColor !== null) $cellColor = $colorSelector->getColor($cellColor);
        else $cellColor = new ImageColor(0,0,0);

        $backgroundColor = $_options->getoption($this->optionPrefix . "OutputBackgroundColor");
        if ($backgroundColor !== null) $backgroundColor = $colorSelector->getColor($backgroundColor);
        else $backgroundColor = new ImageColor(255, 255,255);

        $gridColor = $_options->getoption($this->optionPrefix . "OutputGridColor");
        if ($gridColor !== null) $gridColor = $colorSelector->getColor($gridColor);
        else $gridColor = new ImageColor(0, 0, 0);

        // initialize ImageCreator for this PngOutput
        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor, $this->imageOutputDirectory);
    }
}