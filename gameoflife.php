<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

require_once("Psr4Autoloader.php");

$loader = new Psr4Autoloader();
$loader->addNamespace("GameOfLife", __DIR__ . "/src/Classes/");
$loader->addNamespace("Ulrichsg", __DIR__ . "/src/Ulrichsg/");
$loader->addnameSpace("Input", __DIR__ . "/src/Classes/Inputs");
$loader->register();


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

        // other options
        array(null, "version", Getopt::NO_ARGUMENT, "Print script version"),
        array("h", "help", Getopt::NO_ARGUMENT)
    )
);


// save which options refer to which input type
$inputOptions = array();

// find every input class
foreach (glob(__DIR__ . "/src/Classes/Inputs/*Input.php") as $inputClass)
{
    // get class name with namespace prefix
    $className = "Input\\" . basename($inputClass, ".php");

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
            $inputOptions[$optionName] = $input;
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

    // find out whether user used the --input option
    if ($options->getOption("input"))
    {
        $className = "Input\\" . $options->getOption("input") . "Input";

        if (class_exists($className)) $input = new $className;
    }

    // find out whether any input specific option is set
    foreach ($inputOptions as $inputOption=>$className)
    {
        // if input specific option is set initialize new input of the class which the input refers to
        if ($options->getOption($inputOption) !== null)
        {
            $input = new $className;
        }
    }

    $input->fillBoard($board, $options);

    // Game loop
    $curStep = 0;

    while ($board->isFinished($curStep) == false)
    {
        $curStep++;
        echo "\n\nGame Step: " . $curStep;

        $board->printBoard();
        $board->calculateStep();

        // wait for 0.1 seconds before printing the next board
        usleep(10000);
    }
}