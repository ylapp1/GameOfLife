<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output;

use GameOfLife\Board;
use Output\Helpers\ColorSelector;
use Output\Helpers\ImageCreator;
use Ulrichsg\Getopt;
use Utils\FileSystem\FileSystemReader;
use Utils\FileSystem\FileSystemWriter;

/**
 * Parent class for all classes that output images or process temporary output images.
 */
abstract class ImageOutput extends BaseOutput
{
    /**
     * Output directory for file outputs.
     *
     * @var String $outputDirectory
     */
    protected $baseOutputDirectory = __DIR__ . "/../../../Output";

    /**
     * The file system reader
     *
     * @var FileSystemReader $fileSystemReader
     */
    protected $fileSystemReader;

    /**
     * The file system writer
     *
     * @var FileSystemWriter $fileSystemWriter
     */
    protected $fileSystemWriter;

    /**
     * The image creator
     *
     * @var ImageCreator $imageCreator
     */
    protected $imageCreator;

    /**
     * The output directory for images
     *
     * @var string $imageOutputDirectory
     */
    protected $imageOutputDirectory;

    /**
     * Prefix for all Getopt options for the child class.
     *
     * @var string $optionPrefix
     */
    protected $optionPrefix;


    /**
     * ImageOutput constructor.
     *
     * @param String $_outputTitle The title of the output
     * @param String $_optionPrefix Prefix for all options of this output
     * @param String $_imageOutputDirectory The directory in which the images will be saved
     */
    public function __construct(String $_outputTitle, String $_optionPrefix, String $_imageOutputDirectory)
    {
        parent::__construct($_outputTitle);
        $this->fileSystemReader = new FileSystemReader();
        $this->fileSystemWriter = new FileSystemWriter();
        $this->imageOutputDirectory = $this->baseOutputDirectory . "/" . $_imageOutputDirectory;
        $this->optionPrefix = $_optionPrefix;
    }


    /**
     * Returns the base output directory.
     *
     * @return String The base output directory
     */
    public function baseOutputDirectory(): String
    {
        return $this->baseOutputDirectory;
    }

    /**
     * Sets the base output directory.
     *
     * @param String $_baseOutputDirectory The base output directory
     */
    public function setBaseOutputDirectory(String $_baseOutputDirectory)
    {
        $this->baseOutputDirectory = $_baseOutputDirectory;
    }

    /**
     * Returns the filesystem writer of this output.
     *
     * @return FileSystemWriter Filesystem writer
     */
    public function fileSystemHandler(): FileSystemWriter
    {
        return $this->fileSystemWriter;
    }

    /**
     * Sets the filesystem handler of this output.
     *
     * @param FileSystemWriter $_fileSystemWriter Filesystem writer
     */
    public function setFileSystemHandler(FileSystemWriter $_fileSystemWriter)
    {
        $this->fileSystemWriter = $_fileSystemWriter;
    }

    /**
     * Returns the image creator of this output.
     *
     * @return ImageCreator The image creator
     */
    public function imageCreator(): ImageCreator
    {
        return $this->imageCreator;
    }

    /**
     * Sets the image creator of this output.
     *
     * @param ImageCreator $_imageCreator The image creator
     */
    public function setImageCreator(ImageCreator $_imageCreator)
    {
        $this->imageCreator = $_imageCreator;
    }

    /**
     * Returns the image output directory.
     *
     * @return string Image output directory
     */
    public function imageOutputDirectory(): string
    {
        return $this->imageOutputDirectory;
    }

    /**
     * Sets the image output directory.
     *
     * @param string $_imageOutputDirectory Image output directory
     */
    public function setImageOutputDirectory(string $_imageOutputDirectory)
    {
        $this->imageOutputDirectory = $_imageOutputDirectory;
    }

    /**
     * Returns the option prefix of this image output.
     *
     * @return string Option prefix
     */
    public function optionPrefix(): string
    {
        return $this->optionPrefix;
    }

    /**
     * Sets the option prefix for this image output.
     *
     * @param string $_optionPrefix Option prefix
     */
    public function setOptionPrefix(string $_optionPrefix)
    {
        $this->optionPrefix = $_optionPrefix;
    }


    /**
     * Adds the image options to the option list.
     *
     * @param Getopt $_options Current option list
     */
    public function addOptions(Getopt $_options)
    {
        $className = basename(str_replace("\\", "/", get_called_class()));

        $_options->addOptions(
            array(
                array(null, $this->optionPrefix . "OutputSize", Getopt::REQUIRED_ARGUMENT, $className . " - Size of a cell in pixels"),
                array(null, $this->optionPrefix . "OutputCellColor", Getopt::REQUIRED_ARGUMENT, $className . " - Color of a cell"),
                array(null, $this->optionPrefix . "OutputBackgroundColor", Getopt::REQUIRED_ARGUMENT, $className . " - Background color"),
                array(null, $this->optionPrefix . "OutputGridColor", Getopt::REQUIRED_ARGUMENT, $className . " - Grid color\n")
            )
        );
    }

    /**
     * Initializes the image creator.
     *
     * @param Getopt $_options User inputted option list
     * @param Board $_board Initial board
     *
     * @throws \Exception The exception when one of the input colors is invalid
     */
    public function startOutput(Getopt $_options, Board $_board)
    {
        parent::startOutput($_options, $_board);

        try
        {
            $this->fileSystemWriter->createDirectory($this->imageOutputDirectory);
        }
        catch (\Exception $_exception)
        {
            // Ignore the exception
        }

        $colorSelector = new ColorSelector();

        // fetch options
        $cellSize = $_options->getOption($this->optionPrefix . "OutputSize");
        if ($cellSize !== null) $cellSize = (int)$cellSize;
        else $cellSize = 100;

        $cellColorString = $_options->getOption($this->optionPrefix . "OutputCellColor");
        if ($cellColorString === null) $cellColorString = "black";
        $cellColor = $colorSelector->getColor($cellColorString);

        $backgroundColorString = $_options->getoption($this->optionPrefix . "OutputBackgroundColor");
        if ($backgroundColorString === null) $backgroundColorString = "white";
        $backgroundColor = $colorSelector->getColor($backgroundColorString);

        $gridColorString = $_options->getoption($this->optionPrefix . "OutputGridColor");
        if ($gridColorString === null) $gridColorString = "black";
        $gridColor = $colorSelector->getColor($gridColorString);

        // initialize the ImageCreator
        $this->imageCreator = new ImageCreator($_board->height(), $_board->width(), $cellSize, $cellColor, $backgroundColor, $gridColor);
    }

    /**
     * Returns a new game id for classes that output files.
     *
     * @param String $_outputType Output Type (PNG, Gif, Video)
     *
     * @return int New Game id
     */
    public function getNewGameId(String $_outputType): int
    {
        try
        {
            $fileNames = $this->fileSystemReader->getFileList($this->baseOutputDirectory . "/" . $_outputType, "Game_*");
        }
        catch (\Exception $_exception)
        {
            $fileNames = array();
        }

        if (count($fileNames) == 0) $newGameId = 1;
        else
        {
            $fileIds = array();
            foreach ($fileNames as $fileName)
            {
                $fileData = explode("_", basename($fileName));
                $fileIds[] = intval($fileData[1]);
            }

            sort($fileIds, SORT_NUMERIC);
            $newGameId = $fileIds[count($fileIds) - 1] + 1;
        }

        return $newGameId;
    }
}
