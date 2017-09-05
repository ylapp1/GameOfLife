<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

$loader = require_once(__DIR__ . "/vendor/autoload.php");
$loader->addPsr4("GameOfLife\\", __DIR__ . "/src/GameOfLife");
$loader->addPsr4("Ulrichsg\\", __DIR__ . "/src/Ulrichsg");
$loader->addPsr4("Input\\", __DIR__ . "/src/GameOfLife/Inputs");
$loader->addPsr4("Output\\", __DIR__ . "/src/GameOfLife/Outputs");


use GameOfLife\Board;
use GameOfLife\RuleSet;
use Ulrichsg\Getopt;

// Create command line options
$options = new Getopt(
    array(
        // board options
        array(null, "width", Getopt::REQUIRED_ARGUMENT, "Set the board width (Default: 20)"),
        array(null, "height", Getopt::REQUIRED_ARGUMENT, "Set the board height (Default: 10)"),
        array(null, "maxSteps", Getopt::REQUIRED_ARGUMENT, "Set the maximum amount of steps that are calculated before the simulation stops (Default: 50)"),
        array(null, "border", Getopt::REQUIRED_ARGUMENT, "Set the border type (solid|passthrough) (Default: solid)"),
        array(null, "input", Getopt::REQUIRED_ARGUMENT, "Fill the board with cells (valid arguments: Blinker, Glider, Random, Spaceship)"),
        array(null, "output", Getopt::REQUIRED_ARGUMENT, "Set the output type (valid arguments: console, png)"),

        // other options
        array(null, "version", Getopt::NO_ARGUMENT, "Print script version"),
        array("h", "help", Getopt::NO_ARGUMENT)
    )
);


// save which options refer to which input/output type
$linkedOptions = array();

$classes = array_merge(glob(__DIR__ . "/src/GameOfLife/Inputs/*Input.php"), glob(__DIR__ . "/src/GameOfLife/Outputs/*Output.php"));

// find every input class
foreach ($classes as $inputClass)
{
    // get class name with namespace prefix
    if (stristr($inputClass, "Input") != false) $className = "Input\\";
    elseif (stristr($inputClass, "Output") != false) $className = "Output\\";

    $className .= basename($inputClass, ".php");

    // get options before class adds its options
    $previousOptions = $options->getOptionList();

    // initialize the class
    $input = new $className;
    $input->addOptions($options);

    // get options after the class added its options
    $newOptions = $options->getOptionList();

    // save new options in $inputOptions
    // cannot use array_diff because it doesn't work with multidimensional arrays
    foreach ($newOptions as $newOption)
    {
        $isNewOption = true;

        foreach ($previousOptions as $previousOption)
        {
            if ($previousOption == $newOption)
            {
                $isNewOption = false;
                break;
            }
        }

        if ($isNewOption)
        {
            $optionName = $newOption[1];
            $linkedOptions[$optionName] = $className;
        }
    }
}

// parse options
$options->parse();

if ($options->getOption("version"))
{
    // Show game version
    echo "Game of life version 0.1\n";
    return;
}
elseif ($options->getOption("help"))
{
    // Show help screen
    echo "\n";
    $options->showHelp();
    return;
}
else
{
    // Fetch game options
    $width = $options->getOption("width");
    $height = $options->getOption("height");
    $maxSteps = $options->getOption("maxSteps");
    $border = $options->getOption("border");

    // Fill with default values if options not set
    if ($width == null) $width = 20;
    if ($height == null) $height = 10;
    if ($maxSteps == null) $maxSteps = 50;

    if ($border == null or $border == "solid") $hasBorder = true;
    elseif ($border == "passthrough") $hasBorder = false;
    else
    {
        echo "Error: Invalid border type specified";
        return;
    }

    // define rules for conways game of life
    $rulesConway = new RuleSet(array(3), array(0, 1, 4, 5, 6, 7, 8));

    // initialize new board
    $board = new Board($width, $height, $maxSteps, $hasBorder, $rulesConway);

    // initialize new input with default value
    $input = new Input\RandomInput;
    $output = new Output\ConsoleOutput;

    // find out whether user used the --input option
    if ($options->getOption("input"))
    {
        $className = "Input\\" . $options->getOption("input") . "Input";

        if (class_exists($className)) $input = new $className;
    }

    // find out whether user used the --output option
    if ($options->getOption("output"))
    {
        $className = "Output\\" . $options->getOption("output") . "Output";

        if (class_exists($className)) $output = new $className;
    }

    // find out whether any input/output specific option is set
    foreach ($linkedOptions as $option => $className)
    {
        // if input specific option is set initialize new input of the class which the input refers to
        if ($options->getOption($option))
        {
            if (stristr($className, "Input") != false) $input = new $className;
            elseif (stristr($className, "Output") != false) $output = new $className;
        }
    }

    $input->fillBoard($board, $options);
    $output->startOutput($options, $board);

    // Game loop
    while ($board->isFinished() == false)
    {
        $output->outputBoard($board);
        $board->calculateStep();
    }

    $output->finishOutput();
}